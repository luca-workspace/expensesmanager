<!DOCTYPE html>
<html>
    <head>
      <title>Expenses Overview</title>

      <meta name=viewport content="width=device-width, partial-scale=1">

      <?php
      session_start();

      //check whether the user is authenticated already
      if(!isset($_SESSION["user_id"]))
          header("Location: access.php");
      ?>
      
      <meta charset="UTF-8">
      <!--include Tailwind framework-->
      <script src="./JS/tailwind3.4.1.js"></script>
      <!--include daisyUI library-->
      <link href="./CSS/full.min.css" rel="stylesheet" type="text/css" />
      <!--specify default theme-->
      <html data-theme="emerald"></html>
      <!--include Chart.js library-->
      <script src="./JS/chartjs.min.js"></script>

      <style>
        /*make table body's rows of a different color when hovering.
          oklch(var(--b2) is a function defined in daisyUI's documentation that
          refers to a specific color (b2 is the variable , in this case) depending on the current theme*/ 
        tbody tr:hover {
          background-color: oklch(var(--b2));
        }
      </style>

      <script>
        //check if the session theme is set to night or not
        function checkTheme() {
          //request
          let xhr = new XMLHttpRequest();
          //pack up data for transmission
          //the "get" value tells php that the theme is to be retrieved
          const themeData = `theme=${encodeURIComponent("get")}`;

          xhr.onreadystatechange = function() {
            if(xhr.readyState === XMLHttpRequest.DONE)
              if(xhr.status === 200) {
                if(xhr.responseText !== "No theme set") { //if php returns this string, it means that no theme has been set yet
                  //otherwise, according to the theme set, check or uncheck the toggle triggering it
                  if(xhr.responseText === "emerald")
                    document.getElementById("themeToggle").checked = false;
                  else if(xhr.responseText === "night")
                    document.getElementById("themeToggle").checked = true;
                }
              }
              else
                alert("Error in loading theme preference.");
          };

          //send the actual request
          xhr.open("POST", "./PHP/changetheme.php");
          xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
          xhr.send(themeData);
        };
        
        //this function sets the theme; when it is changed, the edit is saved
        function changeTheme() {
          //if the box controlling the theme is checked, then the theme is light ("emerald"), otherwise dark ("night")
          let checkBox = document.getElementById("themeToggle");
          
          let theme;

          if(checkBox.checked)
            theme = "night";
          else
            theme = "emerald";

          //request, as before
          let xhr = new XMLHttpRequest();
          const themeData = `theme=${encodeURIComponent(theme)}`;

          xhr.onreadystatechange = function() {
            if(xhr.readyState === XMLHttpRequest.DONE)
              if(!xhr.status === 200)
                alert("Error in saving theme preference.");
          };

          xhr.open("POST", "./PHP/changetheme.php");
          xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
          xhr.send(themeData);
        }
      </script>

      <!--script for showing/hiding tabs-->
      <script>
        function showTab(tabN) {
          //retrieve all the tabs' references
          let tabs = [
            document.getElementById("homePage"),
            document.getElementById("addExpenses"),
            document.getElementById("addRevenues"),
            document.getElementById("statsPage")
          ];
          
          //find visible tab by checking if "hidden" is included in their list of classes
          let visibleTab = tabs.find(tab => !tab.classList.contains("hidden"));
          if(visibleTab) //if the one is found...
            visibleTab.classList.add("hidden"); //...hide it

          //if the home page is requested, update data
          if(tabN === 1)
            getHomeData();

          //make the requested tab visible
          tabs[tabN - 1].classList.remove("hidden");
          
          //hide side menu
          document.getElementById("my-drawer").checked = false;
        }
      </script>

      <script>
        let isAccountDeletionConfirmed = false;

        function deteteAccount() {
          //hide side menu
          document.getElementById("my-drawer").checked = false;

          //request, as before
          let xhr = new XMLHttpRequest();
          xhr.open("GET", "./PHP/deleteaccount.php", true);

          xhr.onreadystatechange = function() {
            if(xhr.readyState == XMLHttpRequest.DONE) {
              if(xhr.status == 200) {
                alert("Deleted!");

                //redirect to the log in / sign up page
                window.location.href = "./access.php";
              }
              else {
                alert("An error occurred!");
              }
            }
          }

          //----------------------------------------------------------------------------
          //if this value gets changed in the meantime by the under-defined "cancelDeletion", function, the deletion is canceled
          isAccountDeletionConfirmed = true;
          document.getElementById("accountDeletionModal").showModal();

          const countdownValue = document.getElementById("countdownValue");
          let countdownTime = 4; //initial value. not set to "5" due to the time it takes for the modal to pop and the countdown to start.

          //set interval of 1000 ms (1 s); every second, the countdownTime variable's value is decremented by 1
          const countdownInterval = setInterval(() => {
            //set value through a daisyUI specific property, which allows for the animation etc.
            countdownValue.style.setProperty("--value", countdownTime);
            countdownTime--;

            //if the variable, as said above, has been assigned "false" in the meantime, the interval is cleared, meaning that the countdown has stopped
            if(!isAccountDeletionConfirmed) {
              clearInterval(countdownInterval);
              /*reset value, in case the operation is started again (or the initial value wouldn't be 5,
                even if this wouldn't influence the available time to cancel, as this is still influenced by the countdownTime initial value)*/
              document.getElementById("countdownValue").style.setProperty("--value", 5);
            }

            //if 5 seconds have passed...
            if(countdownTime < -1) {
              //block the displayed value to 0
              countdownValue.style.setProperty("--value", 0);

              //send the deletion request defined above.
              xhr.send();

              //stop the countdown
              clearInterval(countdownInterval);
            }
          }, 1000);
        }

        function cancelDeletion() {
          isAccountDeletionConfirmed = false;
        }
      </script>

      <!--script for AJAX, required to run PHP code that retrieves data from the database for the table, without refreshing-->
      <script>
        let expensesCategories;

        function getHomeData() {
          //show loader
          document.getElementById("loader").classList.remove("hidden");
          
          let xhr = new XMLHttpRequest();

          //configure the request
          xhr.open("GET", "./PHP/homepage.php", true);

          //function to handle the response
          xhr.onreadystatechange = function() {
            if(xhr.readyState == XMLHttpRequest.DONE) {
              if(xhr.status == 200) {
                //the response is in JSON format, and contains multiple data
                let response = JSON.parse(xhr.responseText);

                //the body of the table, with data from the database
                document.getElementById("tbody").innerHTML = response.tbody;

                //the values to be put next to the chart
                document.getElementById("income").innerText = response.data.revenues;
                document.getElementById("expense").innerText = response.data.expenses;
                document.getElementById("bills").innerText = response.data.bills_expenses;
                document.getElementById("food").innerText = response.data.food_expenses;

                //and the information for the chart itself (labels, colors, and, most importantly, values)
                //this information is passed to a function which updates the chart
                if(doughnutChart !== null) //if chart exists already...
                  doughnutChart.destroy(); //...destroy it first
                createHomepageChart(response.chart);

                //add categories for the expenses
                document.getElementById("expensesCategories").innerHTML = response.categoriesexpenses;

                //save it for further use (the category names will be extracteed and used in the fourth tab)
                expensesCategories = response.categoriesexpenses;
                //extract the categories of the expenses for the checkboxes of the detailed view page
                extractExpensesCategories();

                //add categories for the revenues
                document.getElementById("revenuesCategories").innerHTML = response.categoriesrevenues;

                //add locations for the expenses and for the revenues (the same locations apply)
                document.getElementById("expensesLocation").innerHTML = response.locations;
                document.getElementById("revenuesLocation").innerHTML = response.locations;

                document.getElementById("loader").classList.add("hidden");
              }
              else
                console.error("Error fetching data:", xhr.status);
            }
          };

          //send the request
          xhr.send();
        }

        function extractExpensesCategories() {
          let categories = [];
          let startIndex = 0;

          while(true) {
            //this is the structure for each category: <option disabled selected>Category</option>
            //find the opening character "<"
            let openingTagIndex = expensesCategories.indexOf('<', startIndex);
            if(openingTagIndex === -1) //if not found...
              break; //...then no more options are present

            //find the closing character
            let closingTagIndex = expensesCategories.indexOf('>', openingTagIndex);

            //the category begins right after the closing tag
            let categoryStartIndex = closingTagIndex + 1;
            //and it finishes right before the next opening one
            let nextOpeningTagIndex = expensesCategories.indexOf('/', categoryStartIndex);

            //extract the category
            let category = expensesCategories.substring(categoryStartIndex, nextOpeningTagIndex - 1);

            //save it to the array
            categories.push(category);

            //update the index for the next opening character
            startIndex = nextOpeningTagIndex;
          }

          expensesCategories = categories;

          //create list of checkboxes with those categories
          createExpensesCategoriesCheckboxesList();
        }

        function createExpensesCategoriesCheckboxesList() {
          let listHTML = "";

          //build the list
          for(let i = 1; i < expensesCategories.length; i++) { //start from the second element as it is the disabled one
            listHTML += "<tr><td class=\"p-0\"><div class=\"form-control join\"><label class=\"label space-x-0 cursor-pointer\"><input type=\"checkbox\" class=\"join-item checkbox checkbox-secondary\"><span class=\"join-item label-text\" style=\"position: absolute; margin-left: 40px;\">";
            listHTML += expensesCategories[i];
            listHTML += "</span></label></div></td></tr>";
          }

          document.getElementById("expensesCategoriesList").innerHTML = listHTML;
        }
      </script>

      <!--this script is executed as soon as the page has been fully loaded-->
      <script>
        document.addEventListener("DOMContentLoaded", function() {
          //this calls the function which checks whether the theme has been changed or not
          checkTheme();
          //this gets the first tab's info
          getHomeData();
          //this displays the first tab
          showTab(1);
        });
      </script>

      <script>
        let doughnutChart = null;

        function createHomepageChart(chart) {
          //create an instance of the pie chart in canvas with ID "pieChart"
          doughnutChart = new Chart(document.getElementById("homepageChart"), {
            //CHART TYPE
            type: "doughnut", //the type of chart
            
            //CHART DATA
            data: { //the data represented by the chart
              //labels
              labels: chart.labels,
              //colors and values
              datasets: [{
                label: "Last 30 days",
                backgroundColor: chart.colors,
                data: chart.values
              }]
            },

            //CHART CUSTOMIZATIONS
            options: {
              plugins: {
                //legend
                legend: {
                  display: true,
                  position: "left"
                }
              }
            },
          });
        }
      </script>

      <script>
        //this is about the two charts in the detailed view tab and it follows pretty much the same methodologies already implemented above
        let expensesDetailedChart = null;
        let revenuesDetailedChart = null;

        function getDetailedChartsData() {
          document.getElementById("loader").classList.remove("hidden");

          const formData = `year=${encodeURIComponent(Number(document.getElementById("year").innerHTML))}`;

          let xhr = new XMLHttpRequest();
          xhr.open("POST", "./PHP/statisticspage1.php");
          xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

          xhr.onreadystatechange = function() {
            if(xhr.readyState == XMLHttpRequest.DONE) {
              if(xhr.status == 200) {
                if(xhr.responseText !== "-1") {
                  let response = JSON.parse(xhr.responseText);

                  createExpensesDetailedChart(response.datasetsexpenses);
                  createRevenuesDetailedChart(response.datasetsrevenues);

                  document.getElementById("loader").classList.add("hidden");
                }
                else {
                  document.getElementById("loader").classList.add("hidden");

                  document.getElementById("text").innerHTML = "No data! Add some records first";
                  document.getElementById("errorModal").showModal();
                }
              }
              else
                console.error("Error:", xhr.statusText);
            }
          };

          xhr.send(formData);

          //show charts
          document.getElementById("chartsSection").style.display = "block";

          //set the minimum and maximum dates for the detailed table, so that only dates within the same year are available
          let startDate = document.getElementById("startDate");
          let endDate = document.getElementById("endDate");

          let minDate = new Date(document.getElementById("year").innerHTML + "-01-01").toISOString().split('T')[0];
          let maxDate = new Date(document.getElementById("year").innerHTML + "-12-31").toISOString().split('T')[0];

          startDate.setAttribute("min", minDate);
          startDate.setAttribute("max", maxDate);

          endDate.setAttribute("min", minDate);
          endDate.setAttribute("max", maxDate);


          //show the second part of the tab
          document.getElementById("tableSectionFirstPart").style.display = "";
          document.getElementById("tableSectionDevider").style.display = "";
        }

        function createExpensesDetailedChart(chartdata) {
          const labels = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

          const ctx = document.getElementById("expensesDetailedChart").getContext("2d");

          if(expensesDetailedChart)
            expensesDetailedChart.destroy();

          expensesDetailedChart = new Chart(ctx, {
            type: "bar",
            data: {
              labels: labels,
              datasets: chartdata
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                title: {
                  display: true,
                  text: "Expenses"
                },
                legend: {
                  display: false
                }
              },
              scales: {
                x: {
                  stacked: true
                },
                y: {
                  stacked: true
                }
              }
            }
          });
        }

        function createRevenuesDetailedChart(chartdata) {
          const labels = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

          const ctx = document.getElementById("revenuesDetailedChart").getContext("2d");

          if(revenuesDetailedChart)
            revenuesDetailedChart.destroy();

          revenuesDetailedChart = new Chart(ctx, {
            type: "bar",
            data: {
              labels: labels,
              datasets: chartdata
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                title: {
                  display: true,
                  text: "Revenues"
                },
                legend: {
                  display: false
                }
              },
              scales: {
                x: {
                  stacked: true
                },
                y: {
                  stacked: true
                }
              }
            }
          });
        }
      </script>

      <script>
        //this is the function which sends a request to add the data inserted by the user; again, an AJAX request is defined and sent.
        function sendExpense() {
          document.getElementById("loader").classList.remove("hidden");

          //this is a string encoding each value so that it is easy to retrieve them with PHP
          const formData = `expenseAmount=${encodeURIComponent(document.getElementById("expenseAmount").value)}&expenseCategory=${encodeURIComponent(document.getElementById("expensesCategories").value)}&expenseDescription=${encodeURIComponent(document.getElementById("expenseDescription").value)}&expenseLocation=${encodeURIComponent(document.getElementById("expensesLocation").value)}&expenseDate=${encodeURIComponent(document.getElementById("expenseDate").value)}`;

          let xhr = new XMLHttpRequest();
          xhr.open("POST", "./PHP/addexpense.php");
          xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

          xhr.onreadystatechange = function() {
            if(xhr.readyState == XMLHttpRequest.DONE) {
              if(xhr.status == 200) {
                if(xhr.responseText === "0") {
                  document.getElementById("loader").classList.add("hidden");
                  
                  document.getElementById("text").innerHTML = "Record added successfully!";
                  document.getElementById("errorModal").showModal();
                }
                else if(xhr.responseText === "-1") {
                  document.getElementById("loader").classList.add("hidden");

                  document.getElementById("text").innerHTML = "Generic error. Try again!";
                  document.getElementById("errorModal").showModal();
                }
              }
              else
                console.error("Error:", xhr.statusText);
            }
          };

          //the encoded string is attaached to the request
          xhr.send(formData);
        }
      </script>

      <script>
        //this is equivalent to the function above; the difference is that it handles the revenue submission instead of the expense one
        function sendRevenue() {
          document.getElementById("loader").classList.remove("hidden");

          const formData = `revenueAmount=${encodeURIComponent(document.getElementById("revenueAmount").value)}&revenueCategory=${encodeURIComponent(document.getElementById("revenuesCategories").value)}&revenueDescription=${encodeURIComponent(document.getElementById("revenueDescription").value)}&revenueLocation=${encodeURIComponent(document.getElementById("revenuesLocation").value)}&revenueDate=${encodeURIComponent(document.getElementById("revenueDate").value)}`;

          let xhr = new XMLHttpRequest();
          xhr.open("POST", "./PHP/addrevenue.php");
          xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

          xhr.onreadystatechange = function() {
            if(xhr.readyState == XMLHttpRequest.DONE) {
              if(xhr.status == 200) {
                if(xhr.responseText === "0") {
                  document.getElementById("loader").classList.add("hidden");
                  
                  document.getElementById("text").innerHTML = "Record added successfully!";
                  document.getElementById("errorModal").showModal();
                }
                else if(xhr.responseText === "-1") {
                  document.getElementById("loader").classList.add("hidden");

                  document.getElementById("text").innerHTML = "Generic error. Try again!";
                  document.getElementById("errorModal").showModal();
                }
              }
              else
                console.error("Error:", xhr.statusText);
            }
          };

          xhr.send(formData);
        }
      </script>

      <script>
        function changeYear(amount) {
          if(document.getElementById("year").innerHTML === "year") //if one of the coloured buttons is clicked for the first time...
            document.getElementById("year").innerHTML = 2000; //...set the its value to the year 2000 by default
          else
            document.getElementById("year").innerHTML = Number(document.getElementById("year").innerHTML) + amount; //or add or subtract
        }
      </script>

      <script>
        //the same coloured buttons did not seem to work with the join-horizontal and join-vertical classes
        //this is what this script is about: manually switching them
        document.addEventListener("DOMContentLoaded", function() {
          function updateJoinClasses() {
            const viewportWidth = document.documentElement.clientWidth;

            if(viewportWidth <= 402) {
              document.getElementById("joinContainer").classList.remove("join-horizontal");
              document.getElementById("joinContainer").classList.add("join-vertical");
            }
            else {
              document.getElementById("joinContainer").classList.remove("join-vertical");
              document.getElementById("joinContainer").classList.add("join-horizontal");
            }
          }

          //the switch occurs when the window is resized
          window.addEventListener("resize", updateJoinClasses);

          //the same function is called once the page has been fully loaded
          updateJoinClasses();
        });
      </script>

      <script>
        function getDetailedTableData() {
          document.getElementById("loader").classList.remove("hidden");

          let startDate = encodeURIComponent(document.getElementById("startDate").value);
          let endDate = encodeURIComponent(document.getElementById("endDate").value);

          //check which categories are requested
          let checkboxes = document.querySelectorAll('#expensesCategoriesList input[type="checkbox"]');
          let selectedCategories = [];

          checkboxes.forEach((checkbox) => { //for each of the checkboxes...
            if(checkbox.checked) { //...check if they are checked
              //if they are, look for its name, which in this case is contained in the span
              selectedCategories.push(checkbox.parentNode.querySelector('span.label-text').textContent.trim());
              //[put into array the trimmed text of the parent node of class label-text]
            }
          });

          let data = {
            startDate: startDate,
            endDate: endDate,
            categories: selectedCategories
          };

          //------------------------

          //AJAX request, as seen before
          let xhr = new XMLHttpRequest();
          xhr.open("POST", "./PHP/statisticspage2.php");
          xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

          xhr.onreadystatechange = function() {
            if(xhr.readyState == XMLHttpRequest.DONE) {
              if(xhr.status == 200) {
                document.getElementById("tableSectionSecondPart").style.display = "";
                
                document.getElementById("tbody2").innerHTML = xhr.responseText;
                
                document.getElementById("loader").classList.add("hidden");
              }
              else
                console.error("Error:", xhr.statusText);
            }
          };

          //using JSON because it's not single elements, but rather a set of dynamic elements
          xhr.send(JSON.stringify(data));
        }
      </script>

      <script>

        //this functions handle the deletion of a record by its ID and its type (expense or revenue)
        //the methodologies are similar to what has been implemented above
        function triggerRecordDeletionModal() {
          //hide side menu
          document.getElementById("my-drawer").checked = false;

          document.getElementById("recordDeletionModal").showModal();
        }

        function deleteRecord() {
          document.getElementById("loader").classList.remove("hidden");

          const formData = `recordID=${encodeURIComponent(document.getElementById("recordID").value)}&recordType=${encodeURIComponent(document.getElementById("recordType").value)}`;

          let xhr = new XMLHttpRequest();
          xhr.open("POST", "./PHP/deleterecord.php");
          xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

          xhr.onreadystatechange = function() {
            if(xhr.readyState == XMLHttpRequest.DONE) {
              if(xhr.status == 200) {
                if(xhr.responseText === "0") {
                  document.getElementById("loader").classList.add("hidden");
                  
                  document.getElementById("text").innerHTML = "Record deleted successfully!";
                  document.getElementById("errorModal").showModal();
                }
                else if(xhr.responseText === "-1") {
                  document.getElementById("loader").classList.add("hidden");

                  document.getElementById("text").innerHTML = "The record doesn't exist! Check the ID and the type...";
                  document.getElementById("errorModal").showModal();
                }
              }
              else
                console.error("Error:", xhr.statusText);
            }
          };

          xhr.send(formData);
        }
      </script>
    </head>

    <body>
      <!--LOADER-->
      <div id="loader" class="fixed top-0 left-0 w-screen h-screen flex justify-center items-center z-50 hidden" style="backdrop-filter: blur(5px);">
        <span class="loading loading-infinity loading-lg text-info" style="transform: scale(2);"></span>
      </div>

      <!--NAVBAR-->
      <div class="navbar flex items-center justify-between">
        <div class="drawer bg-secondary" style="padding: 0.4rem; border-radius: 10px;">
          <!--side menu button/toggle and content-->
          <input id="my-drawer" type="checkbox" class="drawer-toggle">
          
          <div class="drawer-content join">
            <!--toggle-->
            <label for="my-drawer" class="btn btn-square btn-ghost text-secondary-content drawer-button">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-5 h-5 stroke-current"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </label>

            <!--title-->
            <div class="flex-1">
              <a onclick="showTab(1)" class="btn btn-ghost text-secondary-content text-xl"><span class="block min-[402px]:hidden">Exp. Tr.</span><span class="hidden min-[402px]:block">Expense Tracker</span></a>
            </div>

            <!--light/dark mode (justify-end: align to right | mr-2: 0.5rem margin right)-->
            <label class="cursor-pointer grid place-items-center justify-end mr-2">
              <input id="themeToggle" onclick="changeTheme()" type="checkbox" value="night" class="toggle theme-controller bg-base-content row-start-1 col-start-1 col-span-2">
              <svg class="col-start-1 row-start-1 stroke-base-100 fill-base-100" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><path d="M12 1v2M12 21v2M4.2 4.2l1.4 1.4M18.4 18.4l1.4 1.4M1 12h2M21 12h2M4.2 19.8l1.4-1.4M18.4 5.6l1.4-1.4"></path></svg>
              <svg class="col-start-2 row-start-1 stroke-base-100 fill-base-100" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
            </label>

            <!--three dots...-->
            <form method="post" action="./PHP/logout.php">
              <button class="btn btn-ghost text-secondary-content">
                <a>Log out</a>
              </button>
            </form>

          </div>
          <!--side menu content-->
          <div class="drawer-side z-50">
            <label for="my-drawer" aria-label="close sidebar" class="drawer-overlay"></label>
            <ul class="menu p-4 w-80 min-h-full bg-base-200 text-base-content">
              <li onclick="showTab(1)"><a><b>Overview</b></a></li>
              <li onclick="showTab(2)"><a><b>Add expense(s)</b></a></li>
              <li onclick="showTab(3)"><a><b>Add revenue(s)</b></a></li>
              <li onclick="triggerRecordDeletionModal()"><a class="text-accent"><b>Delete record by ID</b></a></li>
              <li onclick="showTab(4)"><a><b>Detailed view &#128270;</b></a></li>
              <li onclick="deteteAccount()"><a class="text-error"><b>Permanently close account</b></a></li>
            </ul>
          </div>
        </div>
      </div>

      <!--HOME PAGE-->
      <div id="homePage" class="hidden">
        <!--content (mb-4: 1rem margin top)-->
        <div class="grid justify-center gap-8 mt-4 md:content-center">

          <section class="flex flex-col -mt-12 md:flex-row items-center md:space-x-4 space-y-4 xl:space-x-16">
            <!--chart (w-96: 23rem width)-->
            <div class="w-80 h-80 min-[384px]:w-96 min-[384px]:h-96 lg:mr-8 min-[1178px]:mr-0">
              <canvas id="homepageChart"></canvas>
            </div>
            
            <!--some stats-->
            <div class="flex flex-col items-center min-[1178px]:flex-row min-[1178px]:space-x-4 xl:space-x-8">

              <div class="stats -mt-[65px] min-[384px]:-mt-8 md:mt-0 max-w-[362px] max-h-[144px] max-[384px]:scale-[85%]">
                <div class="stat bg-primary min-w-[181px] max-w-[181px]">
                  <div class="stat-title text-accent-content">Income</div>
                  <div id="income" class="stat-value text-success-content"></div>
                  <div class="stat-actions">
                    <button class="btn btn-sm btn-success" onclick="showTab(3)">Add funds</button>
                  </div>
                </div>
                <div class="stat bg-accent min-w-[181px] max-w-[181px]">
                  <div class="stat-title text-accent-content">Outcome</div>
                  <div id="expense" class="stat-value text-error-content"></div>
                  <div class="stat-actions">
                    <button class="btn btn-sm btn-error" onclick="showTab(2)">Add expense(s)</button>
                  </div>
                </div>
              </div>

              <!--more stats-->
              <div class="stats min-[384px]:mt-4 min-[1178px]:mt-0 max-w-[362px] max-h-[144px] max-[384px]:scale-[85%]">
                <div class="stat bg-base-200 min-w-[181px] max-w-[181px]">
                  <div class="stat-title">Bills</div>
                  <div id="bills" class="stat-value"></div>
                  <div class="stat-actions">
                    <button class="btn btn-sm bg-base-300" onclick="showTab(4)">View more</button>
                  </div>
                </div>
                <div class="stat bg-base-200 min-w-[181px] max-w-[181px]">
                  <div class="stat-title">Food</div>
                  <div id="food" class="stat-value"></div>
                  <div class="stat-actions">
                    <button class="btn btn-sm bg-base-300" onclick="showTab(4)">View more</button>
                  </div>
                </div>
              </div>
            </div>
          </section>

        </div>

        <div class="flex flex-col mt-8 md:-mt-2 min-[1178px]:-mt-4 -mb-8">
          <!--general (table) (md:w-4/5: reduce width from middle-sized screens up)-->
          <div class="flex justify-center">
            <div class="md:w-4/5">
              <table class="table table-xs border-double rounded-lg">
                <thead>
                  <tr>
                    <th >ID</th>
                    <th>Amount</th>
                    <th>Category</th>
                    <th class="hidden sm:table-cell">Description</th>
                    <th class="hidden sm:table-cell">Location</th>
                    <th>Date (y-m-d)</th>
                  </tr>
                </thead> 
                <tbody id="tbody">
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!--ADD EXPENSE(S)-->
      <div id="addExpenses" class="flex justify-center items-center h-screen hidden" style="background-image: url('./img/expenses_blurred.jpg'); background-repeat: no-repeat; background-size: cover; margin-top: -4.8rem;">
        <div id="expensesForm" class="flex flex-col space-y-4 w-1/4 min-w-80 items-center max-[340px]:scale-[95%]">
          <!--amount-->
          <label class="input input-bordered flex items-center gap-2 w-full">
            <!--SVG from https://www.svgrepo.com/-->
            <svg width="32px" height="32px" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path opacity="0.5" fill-rule="evenodd" clip-rule="evenodd" d="M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12Z"/><path fill-rule="evenodd" clip-rule="evenodd" d="M12 5.25C12.4142 5.25 12.75 5.58579 12.75 6V6.31673C14.3804 6.60867 15.75 7.83361 15.75 9.5C15.75 9.91421 15.4142 10.25 15 10.25C14.5858 10.25 14.25 9.91421 14.25 9.5C14.25 8.82154 13.6859 8.10339 12.75 7.84748V11.3167C14.3804 11.6087 15.75 12.8336 15.75 14.5C15.75 16.1664 14.3804 17.3913 12.75 17.6833V18C12.75 18.4142 12.4142 18.75 12 18.75C11.5858 18.75 11.25 18.4142 11.25 18V17.6833C9.61957 17.3913 8.25 16.1664 8.25 14.5C8.25 14.0858 8.58579 13.75 9 13.75C9.41421 13.75 9.75 14.0858 9.75 14.5C9.75 15.1785 10.3141 15.8966 11.25 16.1525V12.6833C9.61957 12.3913 8.25 11.1664 8.25 9.5C8.25 7.83361 9.61957 6.60867 11.25 6.31673V6C11.25 5.58579 11.5858 5.25 12 5.25ZM11.25 7.84748C10.3141 8.10339 9.75 8.82154 9.75 9.5C9.75 10.1785 10.3141 10.8966 11.25 11.1525V7.84748ZM14.25 14.5C14.25 13.8215 13.6859 13.1034 12.75 12.8475V16.1525C13.6859 15.8966 14.25 15.1785 14.25 14.5Z" fill="#1C274C"/></svg>
            
            <input type="number" id="expenseAmount" class="grow" placeholder="Amount spent" />
          </label>

          <!--category selection-->
          <select id="expensesCategories" name="expenseCategory" class="select select-bordered w-full">
            <!--list of options (filled from the database)-->
          </select>

          <!--description text area-->
          <textarea id="expenseDescription" class="textarea textarea-bordered w-full" placeholder="Description (optional)"></textarea>

          <!--location-->
          <select id="expensesLocation" name="expenseLocation" class="select select-bordered w-full">
              <!--list of options (filled from the database)-->
          </select>
          
          <!--date-->
          <label class="input input-bordered flex items-center gap-2 w-full">
            <!--SVG from https://www.svgrepo.com/-->
            <svg width="32px" height="32px" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M6.96006 2C7.37758 2 7.71605 2.30996 7.71605 2.69231V4.08883C8.38663 4.07692 9.13829 4.07692 9.98402 4.07692H14.016C14.8617 4.07692 15.6134 4.07692 16.284 4.08883V2.69231C16.284 2.30996 16.6224 2 17.0399 2C17.4575 2 17.7959 2.30996 17.7959 2.69231V4.15008C19.2468 4.25647 20.1992 4.51758 20.899 5.15838C21.5987 5.79917 21.8838 6.67139 22 8V9H2V8C2.11618 6.67139 2.4013 5.79917 3.10104 5.15838C3.80079 4.51758 4.75323 4.25647 6.20406 4.15008V2.69231C6.20406 2.30996 6.54253 2 6.96006 2Z" fill="#1C274C"/><path opacity="0.5" d="M22 14V12C22 11.161 21.9873 9.66527 21.9744 9H2.00586C1.99296 9.66527 2.00564 11.161 2.00564 12V14C2.00564 17.7712 2.00564 19.6569 3.17688 20.8284C4.34813 22 6.23321 22 10.0034 22H14.0023C17.7724 22 19.6575 22 20.8288 20.8284C22 19.6569 22 17.7712 22 14Z" /><path d="M18 16.5C18 17.3284 17.3284 18 16.5 18C15.6716 18 15 17.3284 15 16.5C15 15.6716 15.6716 15 16.5 15C17.3284 15 18 15.6716 18 16.5Z" fill="#1C274C"/></svg>

            <!--date-->
            <input type="date" id="expenseDate" class="grow" />
          </label>

          <!--submit button-->
          <button type="button" onclick="sendExpense()" class="btn btn-outline btn-wide btn-warning">Submit record</button>
        </div>
      </div>

      <!--ADD REVENUE(S)-->
      <div id="addRevenues" class="flex justify-center items-center h-screen hidden" style="background-image: url('./img/revenues_blurred.jpg'); background-repeat: no-repeat; background-size: cover; margin-top: -4.8rem;">
        <div class="flex flex-col space-y-4 w-1/4 min-w-80 items-center max-[340px]:scale-[95%]">
          <!--amount-->
          <label class="input input-bordered flex items-center gap-2 w-full">
            <!--SVG from https://www.svgrepo.com/-->
            <svg width="32px" height="32px" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path opacity="0.5" fill-rule="evenodd" clip-rule="evenodd" d="M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12Z"/><path fill-rule="evenodd" clip-rule="evenodd" d="M12 5.25C12.4142 5.25 12.75 5.58579 12.75 6V6.31673C14.3804 6.60867 15.75 7.83361 15.75 9.5C15.75 9.91421 15.4142 10.25 15 10.25C14.5858 10.25 14.25 9.91421 14.25 9.5C14.25 8.82154 13.6859 8.10339 12.75 7.84748V11.3167C14.3804 11.6087 15.75 12.8336 15.75 14.5C15.75 16.1664 14.3804 17.3913 12.75 17.6833V18C12.75 18.4142 12.4142 18.75 12 18.75C11.5858 18.75 11.25 18.4142 11.25 18V17.6833C9.61957 17.3913 8.25 16.1664 8.25 14.5C8.25 14.0858 8.58579 13.75 9 13.75C9.41421 13.75 9.75 14.0858 9.75 14.5C9.75 15.1785 10.3141 15.8966 11.25 16.1525V12.6833C9.61957 12.3913 8.25 11.1664 8.25 9.5C8.25 7.83361 9.61957 6.60867 11.25 6.31673V6C11.25 5.58579 11.5858 5.25 12 5.25ZM11.25 7.84748C10.3141 8.10339 9.75 8.82154 9.75 9.5C9.75 10.1785 10.3141 10.8966 11.25 11.1525V7.84748ZM14.25 14.5C14.25 13.8215 13.6859 13.1034 12.75 12.8475V16.1525C13.6859 15.8966 14.25 15.1785 14.25 14.5Z" fill="#1C274C"/></svg>
            
            <input type="number" id="revenueAmount" class="grow" placeholder="Amount earned" />
          </label>

          <!--category selection-->
          <select id="revenuesCategories" name="revenueCategory" class="select select-bordered w-full">
              <!--list of options (filled from the database)-->
          </select>

          <!--description text area-->
          <textarea id="revenueDescription" class="textarea textarea-bordered w-full" placeholder="Description (optional)"></textarea>
          
          <!--location-->
          <select id="revenuesLocation" name="revenueLocation" class="select select-bordered w-full">
              <!--list of options (filled from the database)-->
          </select>

          <!--date-->
          <label class="input input-bordered flex items-center gap-2 w-full">
            <!--SVG from https://www.svgrepo.com/-->
            <svg width="32px" height="32px" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M6.96006 2C7.37758 2 7.71605 2.30996 7.71605 2.69231V4.08883C8.38663 4.07692 9.13829 4.07692 9.98402 4.07692H14.016C14.8617 4.07692 15.6134 4.07692 16.284 4.08883V2.69231C16.284 2.30996 16.6224 2 17.0399 2C17.4575 2 17.7959 2.30996 17.7959 2.69231V4.15008C19.2468 4.25647 20.1992 4.51758 20.899 5.15838C21.5987 5.79917 21.8838 6.67139 22 8V9H2V8C2.11618 6.67139 2.4013 5.79917 3.10104 5.15838C3.80079 4.51758 4.75323 4.25647 6.20406 4.15008V2.69231C6.20406 2.30996 6.54253 2 6.96006 2Z" fill="#1C274C"/><path opacity="0.5" d="M22 14V12C22 11.161 21.9873 9.66527 21.9744 9H2.00586C1.99296 9.66527 2.00564 11.161 2.00564 12V14C2.00564 17.7712 2.00564 19.6569 3.17688 20.8284C4.34813 22 6.23321 22 10.0034 22H14.0023C17.7724 22 19.6575 22 20.8288 20.8284C22 19.6569 22 17.7712 22 14Z" /><path d="M18 16.5C18 17.3284 17.3284 18 16.5 18C15.6716 18 15 17.3284 15 16.5C15 15.6716 15.6716 15 16.5 15C17.3284 15 18 15.6716 18 16.5Z" fill="#1C274C"/></svg>
            
            <!--date component-->
            <input type="date" id="revenueDate" class="grow" />
          </label>

          <!--submit button-->
          <button type="button" onclick="sendRevenue()" class="btn btn-outline btn-wide btn-success">Submit record</button>
        </div>
      </div>

      <!--STATS PAGE-->
      <div id="statsPage" class="hidden">
        <div class="divider divider-accent p-2.5">Detailed charts</div>

        <!--content (mb-4: 1rem margin top; p-4: 1rem padding; space-x-4: 1rem horizontal space between)-->
        <div class="grid items-center md:content-center">
          <div class="flex items-center max-[513px]:flex-col flex-row gap-4 justify-center">
            <div id="joinContainer" class="join max-[402px]:w-72">
              <button class="join-item btn btn-error min-w-[66px]" onclick="changeYear(-100)">-100</button>
              <button class="join-item btn btn-warning min-w-[58px]" onclick="changeYear(-10)">-10</button>
              <button class="join-item btn btn-success min-w-[50px]" onclick="changeYear(-1)">-1</button>
              <div id="yearContainer" class="tooltip" data-tip="year">
                <button id="year" class="join-item btn disabled max-[402px]:w-72">year</button>
              </div>
              <button class="join-item btn btn-success min-w-[50px]" onclick="changeYear(1)">+1</button>
              <button class="join-item btn btn-warning min-w-[58px]" onclick="changeYear(10)">+10</button>
              <button class="join-item btn btn-error min-w-[66px]" onclick="changeYear(100)">+100</button>
            </div>

            <button class="btn btn-outline btn-accent max-[402px]:w-72" onclick="getDetailedChartsData()">Load data</button>
          </div>

          <div id="chartsSection" class="md:items-center mt-4 p-4" style="overflow-x: auto; display: none">
            <!--charts-->
            <div class="flex flex-col min-[631px]:flex-row justify-center space-x-4">
              <div class="min-[1232px]:min-w-fit" style="overflow-x: auto;">
                <div style="height: 400px; width: 600px">
                  <canvas id="expensesDetailedChart"></canvas>
                </div>
              </div>

              <div class="min-[1232px]:min-w-fit" style="overflow-x: auto;">
                <div style="height: 400px; width: 600px">
                  <canvas id="revenuesDetailedChart"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div id="tableSectionDevider" class="divider divider-secondary p-2.5" style="display: none">Table (interval within the same year)</div>

        <div id="tableSectionFirstPart" class="grid justify-center" style="display: none">
          <div class="flex flex-col space-y-3 w-1/4 min-w-80 items-center">
            <!--start date-->
            <div class="indicator w-[198px]">
              <span class="indicator-item indicator-start badge badge-secondary">Start date</span>
              <label class="input input-bordered flex items-center w-full">
                <svg width="32px" height="32px" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M6.96006 2C7.37758 2 7.71605 2.30996 7.71605 2.69231V4.08883C8.38663 4.07692 9.13829 4.07692 9.98402 4.07692H14.016C14.8617 4.07692 15.6134 4.07692 16.284 4.08883V2.69231C16.284 2.30996 16.6224 2 17.0399 2C17.4575 2 17.7959 2.30996 17.7959 2.69231V4.15008C19.2468 4.25647 20.1992 4.51758 20.899 5.15838C21.5987 5.79917 21.8838 6.67139 22 8V9H2V8C2.11618 6.67139 2.4013 5.79917 3.10104 5.15838C3.80079 4.51758 4.75323 4.25647 6.20406 4.15008V2.69231C6.20406 2.30996 6.54253 2 6.96006 2Z" fill="#1C274C"/><path opacity="0.5" d="M22 14V12C22 11.161 21.9873 9.66527 21.9744 9H2.00586C1.99296 9.66527 2.00564 11.161 2.00564 12V14C2.00564 17.7712 2.00564 19.6569 3.17688 20.8284C4.34813 22 6.23321 22 10.0034 22H14.0023C17.7724 22 19.6575 22 20.8288 20.8284C22 19.6569 22 17.7712 22 14Z" /><path d="M18 16.5C18 17.3284 17.3284 18 16.5 18C15.6716 18 15 17.3284 15 16.5C15 15.6716 15.6716 15 16.5 15C17.3284 15 18 15.6716 18 16.5Z" fill="#1C274C"/></svg>
                <input type="date" id="startDate" class="grow" />
              </label>
            </div>

            <!--end date-->
            <div class="indicator w-[198px]">
              <span class="indicator-item indicator-bottom badge badge-secondary">End date</span>
              <label class="input input-bordered flex items-center w-full">
                <svg width="32px" height="32px" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M6.96006 2C7.37758 2 7.71605 2.30996 7.71605 2.69231V4.08883C8.38663 4.07692 9.13829 4.07692 9.98402 4.07692H14.016C14.8617 4.07692 15.6134 4.07692 16.284 4.08883V2.69231C16.284 2.30996 16.6224 2 17.0399 2C17.4575 2 17.7959 2.30996 17.7959 2.69231V4.15008C19.2468 4.25647 20.1992 4.51758 20.899 5.15838C21.5987 5.79917 21.8838 6.67139 22 8V9H2V8C2.11618 6.67139 2.4013 5.79917 3.10104 5.15838C3.80079 4.51758 4.75323 4.25647 6.20406 4.15008V2.69231C6.20406 2.30996 6.54253 2 6.96006 2Z" fill="#1C274C"/><path opacity="0.5" d="M22 14V12C22 11.161 21.9873 9.66527 21.9744 9H2.00586C1.99296 9.66527 2.00564 11.161 2.00564 12V14C2.00564 17.7712 2.00564 19.6569 3.17688 20.8284C4.34813 22 6.23321 22 10.0034 22H14.0023C17.7724 22 19.6575 22 20.8288 20.8284C22 19.6569 22 17.7712 22 14Z" /><path d="M18 16.5C18 17.3284 17.3284 18 16.5 18C15.6716 18 15 17.3284 15 16.5C15 15.6716 15.6716 15 16.5 15C17.3284 15 18 15.6716 18 16.5Z" fill="#1C274C"/></svg>
                <input type="date" id="endDate" class="grow" />
              </label>
            </div>

            <!--options-->
            <div class="overflow-y-auto w-[198px] h-48" style="margin-top: 20px; border: 1px solid oklch(var(--s)); border-radius: 8px;">
              <table class="table table-pin-rows">
                <thead>
                  <tr>
                    <th>Categories</th>
                  </tr>
                </thead>

                <tbody id="expensesCategoriesList">
                  
                </tbody>
              </table>
            </div>

            <div class="overflow-y-auto w-[198px]" style="margin-top: 20px;">
              <button class="btn btn-outline btn-wide btn-secondary max-w-[198px]" onclick="getDetailedTableData()">Load data</button>
            </div>
          </div>
        </div>

        <!--info-->
        <div id="tableSectionSecondPart" class="flex justify-center" style="margin-top: 20px; display: none">
          <div class="md:w-4/5">
            <table class="table table-xs border-double rounded-lg">
              <thead>
                <tr>
                  <th class="hidden sm:block">ID</th>
                  <th>Amount</th>
                  <th>Category</th>
                  <th>Description</th>
                  <th class="hidden sm:block">Location</th>
                  <th>Date (y-m-d)</th>
                </tr>
              </thead> 
              <tbody id="tbody2">

              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!--ACCOUNT DELETION MODAL-->
      <dialog id="accountDeletionModal" class="modal" style="backdrop-filter: blur(5px);">
        <div class="modal-box">
          <h3 class="font-bold text-lg">Warning! Deletion...</h3>
          <p id="deleteAccount" class="py-4"></p>

          <div class="flex flex-row justify-center space-x-8">
            <span class="countdown font-mono text-6xl">
              <span id="countdownValue" style="--value:5;"></span>
            </span>

            <div class="modal-action m-2">
              <form method="dialog">
                <button class="btn btn-outline btn-success" onclick="cancelDeletion()">Cancel, I have changed my mind</button>
              </form>
            </div>
          </div>

          <progress class="progress w-full mt-4"></progress>
        </div>
      </dialog>

      <!--[RECORD DELETION MODAL]-->
      <dialog id="recordDeletionModal" class="modal" style="backdrop-filter: blur(5px);">
        <div class="modal-box">
          <h3 class="font-bold text-lg">Delete record</h3>
          <div class="flex justify-center join mt-8">
            <div>
              <div>
                <input id="recordID" class="input input-bordered join-item" placeholder="ID" type="number">
              </div>
            </div>

            <select id="recordType" class="select select-bordered join-item">
              <option>Expense</option>
              <option>Revenue</option>
            </select>

            <div>
              <button class="btn join-item btn-outline btn-error" onclick="deleteRecord()">Delete</button>
            </div>
          </div>

          <div class="modal-action">
            <form method="dialog">
              <button class="btn" onclick="window.location.reload()">Close</button>
            </form>
          </div>
        </div>
      </dialog>

      <!--[INFO/ERROR MODAL]-->
      <dialog id="errorModal" class="modal">
        <div class="modal-box">
          <h3 class="font-bold text-lg">Message</h3>
          <p id="text" class="py-4"></p>
          <div class="modal-action">
            <form method="dialog">
              <button class="btn">Close</button>
            </form>
          </div>
        </div>
      </dialog>
    </body>
</html>
