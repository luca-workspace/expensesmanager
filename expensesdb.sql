-- -----------------------------------------------------
-- Project: Vacation Rental Platform Database Schema
-- Author: Luca Lodola
-- GitHub: https://github.com/luca-workspace/vacation-rental-db-schema
-- Description: Relational database schema handling users, 
--              properties, bookings, reviews, and payments.
-- -----------------------------------------------------

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;

--
-- Database: `expensesdb`
--
CREATE DATABASE IF NOT EXISTS `expensesdb` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `expensesdb`;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `expenseid` int(11) NOT NULL,
  `amount` float NOT NULL,
  `fk_categoryname` varchar(20) NOT NULL,
  `description` varchar(300) DEFAULT NULL,
  `fk_location` varchar(40) NOT NULL,
  `date` date NOT NULL,
  `fk_userid` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`expenseid`, `amount`, `fk_categoryname`, `description`, `fk_location`, `date`, `fk_userid`) VALUES
(465, 25.75, 'Entertainment', 'Cinema tickets', 'Italy', '2023-08-12', 'test'),
(466, 98.2, 'Restaurants', 'Dinner at Italian restaurant', 'France', '2023-07-23', 'test'),
(467, 12.99, 'General', 'Grocery shopping', 'United States', '2023-06-05', 'test'),
(468, 67.8, 'Transport', 'Gas for car', 'Canada', '2023-09-01', 'test'),
(469, 32.45, 'Health', 'Prescription medication', 'Germany', '2023-05-18', 'test'),
(470, 159.99, 'Investments', 'Stock purchase', 'United Kingdom', '2023-03-11', 'test'),
(471, 24.3, 'Services', 'Haircut', 'Spain', '2023-02-27', 'test'),
(472, 89.75, 'Trips', 'Hotel for weekend getaway', 'Australia', '2023-04-22', 'test'),
(473, 7.85, 'Donations', 'Charity donation', 'Japan', '2023-01-09', 'test'),
(474, 41.2, 'Insurance', 'Car insurance payment', 'Brazil', '2023-07-06', 'test'),
(475, 63.5, 'Entertainment', 'Concert tickets', 'Mexico', '2023-06-15', 'test'),
(476, 28.95, 'General', 'Office supplies', 'Russia', '2023-08-28', 'test'),
(477, 91.65, 'Transport', 'Plane ticket', 'China', '2023-03-19', 'test'),
(478, 17.8, 'Health', 'Dental checkup', 'India', '2023-09-07', 'test'),
(479, 54.25, 'Investments', 'Mutual fund contribution', 'South Africa', '2023-05-02', 'test'),
(480, 33.4, 'Services', 'House cleaning service', 'Turkey', '2023-07-11', 'test'),
(481, 126.75, 'Trips', 'Vacation package', 'Egypt', '2023-02-14', 'test'),
(482, 9.99, 'Donations', 'Non-profit organization donation', 'Argentina', '2023-06-22', 'test'),
(483, 68.3, 'Insurance', 'Health insurance premium', 'Colombia', '2023-04-09', 'test'),
(484, 21.5, 'Entertainment', 'Movie rental', 'Poland', '2023-01-03', 'test'),
(485, 45.8, 'Restaurants', 'Thai food delivery', 'Netherlands', '2023-08-19', 'test'),
(486, 14.25, 'General', 'Household items', 'Sweden', '2023-07-28', 'test'),
(487, 79.95, 'Transport', 'New tires for car', 'Switzerland', '2023-03-06', 'test'),
(488, 23.7, 'Health', 'Vitamin supplements', 'Belgium', '2023-09-14', 'test'),
(489, 102.8, 'Investments', 'Cryptocurrency purchase', 'Portugal', '2023-05-25', 'test'),
(490, 38.6, 'Services', 'Lawn care service', 'Greece', '2023-02-03', 'test'),
(491, 149.5, 'Trips', 'Cruise vacation', 'New Zealand', '2023-08-05', 'test'),
(492, 11.75, 'Donations', 'Animal shelter donation', 'Denmark', '2023-06-29', 'test'),
(493, 82.4, 'Insurance', 'Home insurance payment', 'Norway', '2023-04-16', 'test'),
(494, 27.2, 'Entertainment', 'Streaming service subscription', 'Finland', '2023-01-10', 'test'),
(495, 61.95, 'Restaurants', 'Sushi dinner', 'Ireland', '2023-07-03', 'test'),
(496, 18.4, 'General', 'School supplies', 'Austria', '2023-09-09', 'test'),
(497, 93.7, 'Transport', 'Car rental', 'Chile', '2023-05-11', 'test'),
(498, 31.15, 'Health', 'Massage therapy', 'Venezuela', '2023-03-24', 'test'),
(499, 115.25, 'Investments', 'Real estate investment trust', 'United Arab Emirates', '2023-06-08', 'test'),
(500, 46.8, 'Services', 'Plumbing repair', 'Saudi Arabia', '2023-02-20', 'test'),
(501, 167.5, 'Trips', 'Family vacation package', 'Morocco', '2023-07-16', 'test'),
(502, 13.99, 'Donations', 'Environmental organization donation', 'Israel', '2023-04-03', 'test'),
(503, 94.6, 'Insurance', 'Life insurance premium', 'Kenya', '2023-09-21', 'test'),
(504, 33.75, 'Entertainment', 'Bowling night', 'Nigeria', '2023-01-17', 'test'),
(505, 72.4, 'Restaurants', 'Mexican restaurant dinner', 'Peru', '2023-08-26', 'test'),
(506, 21.8, 'General', 'Toiletries', 'Romania', '2023-05-04', 'test'),
(507, 107.95, 'Transport', 'Car repair', 'Czech Republic', '2023-07-23', 'test'),
(508, 39.5, 'Health', 'Chiropractor visit', 'Hungary', '2023-03-11', 'test'),
(509, 128.8, 'Investments', 'Bond purchase', 'Ukraine', '2023-06-15', 'test'),
(510, 53.25, 'Services', 'Carpet cleaning service', 'Korea, South', '2023-04-28', 'test'),
(511, 184.75, 'Trips', 'International vacation package', 'Singapore', '2023-02-07', 'test'),
(512, 16.45, 'Donations', 'Disaster relief donation', 'Malaysia', '2023-09-02', 'test'),
(513, 106.2, 'Insurance', 'Renter insurance', 'Thailand', '2023-05-18', 'test'),
(514, 40.3, 'Entertainment', 'Sporting event tickets', 'Vietnam', '2023-01-24', 'test'),
(515, 83.95, 'Restaurants', 'Italian restaurant dinner', 'Philippines', '2023-08-09', 'test'),
(516, 26.7, 'General', 'Home decor items', 'Indonesia', '2023-07-05', 'test'),
(517, 119.8, 'Transport', 'Motorcycle purchase', 'Taiwan', '2023-03-29', 'test'),
(518, 47.6, 'Health', 'Physical therapy session', 'Taiwan', '2023-06-22', 'test'),
(519, 142.5, 'Investments', 'Equity fund investment', 'Qatar', '2023-04-05', 'test'),
(520, 59.95, 'Services', 'Home security system installation', 'Kuwait', '2023-02-13', 'test'),
(521, 203.25, 'Trips', 'Luxury resort vacation', 'Bahrain', '2023-07-30', 'test'),
(522, 19.8, 'Donations', 'Educational organization donation', 'Oman', '2023-05-07', 'test'),
(523, 116.4, 'Insurance', 'Business insurance premium', 'Jordan', '2023-09-16', 'test'),
(524, 41.75, 'Entertainment', 'Arcade games', 'Lebanon', '2023-01-31', 'test'),
(525, 94.2, 'Restaurants', 'Steakhouse dinner', 'Iraq', '2023-08-16', 'test'),
(526, 32.55, 'General', 'Pet supplies', 'Iran', '2023-06-01', 'test'),
(527, 136.95, 'Transport', 'Used car purchase', 'Syria', '2023-07-09', 'test'),
(528, 53.8, 'Health', 'Acupuncture treatment', 'Yemen', '2023-03-17', 'test'),
(529, 165.4, 'Investments', 'Private equity investment', 'Libya', '2023-04-22', 'test'),
(530, 67.25, 'Services', 'Pest control service', 'Algeria', '2023-02-27', 'test'),
(531, 221.5, 'Trips', 'Round-the-world trip', 'Tunisia', '2023-08-13', 'test'),
(532, 24.99, 'Donations', 'Youth organization donation', 'Morocco', '2023-05-25', 'test'),
(533, 131.8, 'Insurance', 'Umbrella insurance policy', 'Taiwan', '2023-01-08', 'test'),
(534, 49.4, 'Entertainment', 'Miniature golf', 'Mauritania', '2023-09-24', 'test'),
(535, 105.75, 'Restaurants', 'Japanese restaurant dinner', 'Senegal', '2023-06-08', 'test'),
(536, 37.95, 'General', 'Kitchen supplies', 'The Gambia', '2023-07-17', 'test'),
(537, 154.2, 'Transport', 'Electric car', 'Mali', '2023-03-04', 'test'),
(538, 61.65, 'Health', 'Nutritionist consultation', 'Burkina Faso', '2023-04-10', 'test'),
(539, 188.8, 'Investments', 'Hedge fund investment', 'Niger', '2023-02-21', 'test'),
(540, 74.5, 'Services', 'Moving service', 'Eritrea', '2023-08-03', 'test'),
(541, 239.95, 'Trips', 'Around-the-world cruise', 'Chad', '2023-05-12', 'test'),
(542, 30.25, 'Donations', 'Veterans organization donation', 'Sudan', '2023-01-15', 'test'),
(543, 144.6, 'Insurance', 'Professional liability insurance', 'Ethiopia', '2023-07-24', 'test'),
(544, 56.7, 'Entertainment', 'Escape room experience', 'Djibouti', '2023-06-15', 'test'),
(545, 117.3, 'Restaurants', 'Indian restaurant dinner', 'Somalia', '2023-04-02', 'test'),
(546, 42.8, 'General', 'Garden supplies', 'Eritrea', '2023-09-11', 'test'),
(547, 171.5, 'Transport', 'Luxury car purchase', 'Kenya', '2023-05-29', 'test'),
(548, 68.95, 'Health', 'Gym membership', 'Uganda', '2023-02-05', 'test'),
(549, 35.75, 'Entertainment', 'Movie tickets', 'United States', '2023-09-22', 'test'),
(550, 62.2, 'Restaurants', 'Steakhouse dinner', 'United States', '2023-08-14', 'test'),
(551, 22.99, 'General', 'Stationery supplies', 'United States', '2023-07-03', 'test'),
(552, 87.8, 'Transport', 'Car rental', 'United States', '2023-06-18', 'test'),
(553, 48.45, 'Health', 'Dental cleaning', 'United States', '2023-05-27', 'test'),
(554, 179.99, 'Investments', 'Mutual fund contribution', 'United States', '2023-04-09', 'test'),
(555, 31.3, 'Services', 'Home repair service', 'United States', '2023-03-21', 'test'),
(556, 109.75, 'Trips', 'Weekend getaway', 'United States', '2023-02-11', 'test'),
(557, 14.85, 'Donations', 'Animal shelter donation', 'United States', '2023-01-28', 'test'),
(558, 58.2, 'Insurance', 'Renters insurance', 'United States', '2023-12-05', 'test'),
(559, 73.5, 'Entertainment', 'Concert tickets', 'United States', '2023-11-19', 'test'),
(560, 38.95, 'General', 'Home decor items', 'United States', '2023-10-08', 'test'),
(561, 111.65, 'Transport', 'Airline ticket', 'United States', '2023-09-30', 'test'),
(562, 24.8, 'Health', 'Gym membership', 'United States', '2023-08-21', 'test'),
(563, 64.25, 'Investments', 'Stock purchase', 'United States', '2023-07-12', 'test'),
(564, 43.4, 'Services', 'Landscaping service', 'United States', '2023-06-04', 'test'),
(565, 146.75, 'Trips', 'Caribbean cruise', 'United States', '2023-05-20', 'test'),
(566, 16.99, 'Donations', 'Educational charity', 'United States', '2023-04-15', 'test'),
(567, 78.3, 'Insurance', 'Auto insurance', 'United States', '2023-03-07', 'test'),
(568, 31.5, 'Entertainment', 'Sporting event tickets', 'United States', '2023-02-25', 'test'),
(569, 55.8, 'Restaurants', 'Mexican restaurant', 'United States', '2023-01-13', 'test'),
(570, 24.25, 'General', 'Grocery shopping', 'United States', '2023-12-29', 'test'),
(571, 89.95, 'Transport', 'Gas for car', 'United States', '2023-11-08', 'test'),
(572, 33.7, 'Health', 'Prescription medication', 'United States', '2023-10-23', 'test'),
(573, 112.8, 'Investments', 'ETF purchase', 'United States', '2023-09-17', 'test'),
(574, 48.6, 'Services', 'Cleaning service', 'United States', '2023-08-06', 'test'),
(575, 159.5, 'Trips', 'National park vacation', 'United States', '2023-07-28', 'test'),
(576, 21.75, 'Donations', 'Environmental charity', 'United States', '2023-06-19', 'test'),
(577, 92.4, 'Insurance', 'Life insurance premium', 'United States', '2023-05-11', 'test'),
(578, 37.2, 'Entertainment', 'Video game purchase', 'United States', '2023-04-02', 'test'),
(579, 71.95, 'Restaurants', 'Italian restaurant', 'United States', '2023-03-25', 'test'),
(580, 28.4, 'General', 'Office supplies', 'United States', '2023-02-15', 'test'),
(581, 103.7, 'Transport', 'Car purchase', 'United States', '2023-01-06', 'test'),
(582, 41.15, 'Health', 'Massage therapy', 'United States', '2023-12-22', 'test'),
(583, 125.25, 'Investments', 'Real estate investment', 'United States', '2023-11-13', 'test'),
(584, 56.8, 'Services', 'Plumbing repair', 'United States', '2023-10-04', 'test'),
(585, 177.5, 'Trips', 'Hawaii vacation', 'United States', '2023-09-26', 'test'),
(586, 23.99, 'Donations', 'Veterans charity', 'United States', '2023-08-17', 'test'),
(587, 104.6, 'Insurance', 'Pet insurance', 'United States', '2023-07-09', 'test'),
(588, 43.75, 'Entertainment', 'Bowling night', 'United States', '2023-06-30', 'test'),
(589, 82.4, 'Restaurants', 'Seafood restaurant', 'United States', '2023-05-22', 'test'),
(590, 31.8, 'General', 'Household items', 'United States', '2023-04-13', 'test'),
(591, 117.95, 'Transport', 'Car maintenance', 'United States', '2023-03-06', 'test'),
(592, 49.5, 'Health', 'Nutritionist consultation', 'United States', '2023-02-24', 'test'),
(593, 138.8, 'Investments', 'Cryptocurrency investment', 'United States', '2023-01-16', 'test'),
(594, 63.25, 'Services', 'Handyman service', 'United States', '2023-12-07', 'test'),
(595, 65.25, 'Food', 'Grocery shopping', 'United States', '2023-04-28', 'test'),
(596, 32.8, 'Food', 'Restaurant dinner', 'United States', '2023-05-03', 'test'),
(597, 17.5, 'Bills', 'Electricity bill', 'United States', '2023-05-10', 'test'),
(598, 89.95, 'Food', 'Weekly groceries', 'United States', '2023-05-14', 'test'),
(599, 124.6, 'Bills', 'Internet and cable TV', 'United States', '2023-05-21', 'test'),
(600, 45.75, 'Food', 'Takeout food', 'United States', '2023-05-25', 'test'),
(601, 27.5, 'Entertainment', 'Arcade games', 'United States', '2024-03-28', 'test'),
(602, 45.8, 'Restaurants', 'Thai restaurant', 'United States', '2024-03-27', 'test'),
(603, 19.99, 'General', 'Home cleaning supplies', 'United States', '2024-03-26', 'test'),
(604, 93.2, 'Transport', 'Taxi fare', 'United States', '2024-03-25', 'test'),
(605, 57.45, 'Health', 'Doctor consultation', 'United States', '2024-03-24', 'test'),
(606, 88.99, 'Investments', 'IRA contribution', 'United States', '2024-03-23', 'test'),
(607, 37.3, 'Services', 'Pest control service', 'United States', '2024-03-22', 'test'),
(608, 109.75, 'Trips', 'Ski trip', 'United States', '2024-03-21', 'test'),
(609, 14.85, 'Donations', 'Children charity', 'United States', '2024-03-20', 'test'),
(610, 58.2, 'Insurance', 'Travel insurance', 'United States', '2024-03-19', 'test'),
(611, 73.5, 'Entertainment', 'Amusement park tickets', 'United States', '2024-03-18', 'test'),
(612, 38.95, 'General', 'Laundry detergent', 'United States', '2024-03-17', 'test'),
(613, 111.65, 'Transport', 'Public transportation pass', 'United States', '2024-03-16', 'test'),
(614, 24.8, 'Health', 'Fitness class', 'United States', '2024-03-15', 'test'),
(615, 64.25, 'Investments', 'Bond purchase', 'United States', '2024-03-14', 'test'),
(616, 43.4, 'Services', 'Appliance repair', 'United States', '2024-03-13', 'test'),
(617, 146.75, 'Trips', 'Road trip expenses', 'United States', '2024-03-12', 'test'),
(618, 16.99, 'Donations', 'Humanitarian aid', 'United States', '2024-03-11', 'test'),
(619, 78.3, 'Insurance', 'Health insurance premium', 'United States', '2024-03-10', 'test'),
(620, 31.5, 'Entertainment', 'Netflix subscription', 'United States', '2024-03-09', 'test'),
(621, 45.6, 'Food', 'Grocery shopping', 'United States', '2024-03-05', 'test'),
(622, 52.4, 'Food', 'Takeout dinner', 'United States', '2024-03-25', 'test'),
(623, 83.5, 'Food', 'Weekly groceries', 'United States', '2024-04-02', 'test'),
(624, 49.75, 'Food', 'Pizza delivery', 'United States', '2024-04-28', 'test'),
(625, 37.2, 'Bills', 'Electricity bill', 'United States', '2024-03-20', 'test'),
(626, 29.99, 'Bills', 'Internet bill', 'United States', '2024-04-15', 'test'),
(627, 28.45, 'Food', 'Supermarket snacks', 'United States', '2024-03-12', 'test'),
(628, 17.9, 'Food', 'Convenience store purchases', 'United States', '2024-03-18', 'test'),
(629, 61.3, 'Food', 'Home-cooked meal ingredients', 'United States', '2024-04-05', 'test'),
(630, 42.25, 'Food', 'Takeaway lunch', 'United States', '2024-04-12', 'test'),
(631, 35.6, 'Bills', 'Phone bill', 'United States', '2024-03-10', 'test'),
(632, 57.8, 'Bills', 'Water bill', 'United States', '2024-03-30', 'test'),
(633, 49.9, 'Bills', 'Gas bill', 'United States', '2024-04-10', 'test'),
(712, 85.25, 'Bills', 'Electricity bill', 'Italy', '2024-01-02', 'test'),
(713, 62.8, 'Food', 'Weekly groceries', 'Italy', '2024-01-05', 'test'),
(714, 27.99, 'Entertainment', 'Movie ticket', 'France', '2024-01-12', 'test'),
(715, 149, 'Bills', 'Monthly rent', 'Italy', '2024-01-15', 'test'),
(716, 95.6, 'Transport', 'Gas refill', 'Germany', '2024-01-20', 'test'),
(717, 73.49, 'Health', 'Doctor visit copay', 'Spain', '2024-01-25', 'test'),
(718, 35.75, 'Services', 'Housekeeping service', 'Italy', '2024-01-30', 'test'),
(719, 129.99, 'Entertainment', 'Concert tickets', 'United Kingdom', '2024-02-03', 'test'),
(720, 86.23, 'Transport', 'Train tickets', 'Switzerland', '2024-02-08', 'test'),
(721, 210.67, 'Trips', 'Hotel stay', 'Greece', '2024-02-15', 'test'),
(722, 54.8, 'Food', 'Lunch out', 'Japan', '2024-02-20', 'test'),
(723, 18.99, 'Entertainment', 'Movie rental', 'Italy', '2024-02-25', 'test'),
(724, 275, 'Bills', 'Quarterly taxes', 'Italy', '2024-03-01', 'test'),
(725, 108.75, 'Transport', 'Airline tickets', 'United States', '2024-03-10', 'test'),
(726, 670.5, 'Trips', 'Resort package', 'Mexico', '2024-03-15', 'test'),
(727, 95.25, 'Food', 'Fancy dinner', 'Mexico', '2024-03-20', 'test'),
(728, 32.49, 'Entertainment', 'Museum entry', 'Mexico', '2024-03-25', 'test'),
(729, 28.8, 'Services', 'Haircut', 'Italy', '2024-03-30', 'test'),
(730, 165, 'Bills', 'Bi-annual insurance', 'Italy', '2024-04-05', 'test'),
(731, 74.35, 'Food', 'Groceries', 'Italy', '2024-04-10', 'test'),
(732, 19.99, 'Entertainment', 'Book purchase', 'Italy', '2024-04-15', 'test'),
(733, 62.5, 'Transport', 'Monthly metro pass', 'Italy', '2024-04-20', 'test'),
(734, 85.8, 'Services', 'Plumber visit', 'Italy', '2024-04-25', 'test'),
(735, 115, 'Bills', 'Quarterly TV/Internet', 'Italy', '2024-04-30', 'test'),
(736, 37.25, 'Food', 'Takeout dinner', 'Italy', '2024-05-05', 'test'),
(737, 92.99, 'Entertainment', 'Theater tickets', 'France', '2024-05-10', 'test'),
(738, 178.6, 'Transport', 'Car rental', 'Germany', '2024-05-15', 'test'),
(739, 265.4, 'Trips', 'Airbnb stay', 'Austria', '2024-05-20', 'test'),
(740, 68.9, 'Food', 'Dining out', 'Austria', '2024-05-25', 'test'),
(741, 22.75, 'Services', 'Laundry service', 'Austria', '2024-05-30', 'test'),
(742, 149, 'Bills', 'Monthly rent', 'Italy', '2024-06-01', 'test'),
(743, 85.4, 'Food', 'Meal delivery', 'Italy', '2024-06-05', 'test'),
(744, 65.25, 'Entertainment', 'Event tickets', 'Italy', '2024-06-10', 'test'),
(745, 38.99, 'Transport', 'Taxi rides', 'Italy', '2024-06-15', 'test'),
(746, 125.8, 'Health', 'Dental visit', 'Italy', '2024-06-20', 'test'),
(747, 55.5, 'Services', 'Home repair', 'Italy', '2024-06-25', 'test'),
(748, 93.75, 'Bills', 'Utilities', 'Italy', '2024-06-30', 'test'),
(749, 120, 'Donations', 'Charity gift', 'Switzerland', '2024-07-05', 'test'),
(750, 79.85, 'Food', 'Fancy takeout', 'Italy', '2024-07-10', 'test'),
(751, 34.5, 'Entertainment', 'Streaming services', 'Italy', '2024-07-15', 'test'),
(752, 198.99, 'Transport', 'Car service', 'United Arab Emirates', '2024-07-20', 'test'),
(753, 1265.75, 'Trips', 'Resort vacation', 'United Arab Emirates', '2024-07-25', 'test'),
(754, 275.4, 'Food', 'Luxurious meals', 'United Arab Emirates', '2024-07-30', 'test'),
(755, 68.2, 'Entertainment', 'Desert safari', 'United Arab Emirates', '2024-08-03', 'test'),
(756, 35.99, 'Services', 'Spa treatment', 'United Arab Emirates', '2024-08-08', 'test'),
(757, 149, 'Bills', 'Monthly rent', 'Italy', '2024-08-15', 'test'),
(758, 82.5, 'Food', 'Meal kit delivery', 'Italy', '2024-08-20', 'test'),
(759, 55.25, 'Entertainment', 'Mini golf', 'Italy', '2024-08-25', 'test'),
(760, 22.8, 'Transport', 'Gas', 'Italy', '2024-08-30', 'test'),
(761, 95.4, 'Health', 'Prescription refill', 'Italy', '2024-09-05', 'test'),
(762, 65.75, 'Services', 'Dog grooming', 'Italy', '2024-09-10', 'test'),
(763, 128.55, 'Bills', 'Quarterly insurance', 'Italy', '2024-09-15', 'test'),
(764, 48.99, 'Food', 'Bakery visit', 'France', '2024-09-20', 'test'),
(765, 79.25, 'Entertainment', 'Bowling', 'France', '2024-09-25', 'test'),
(766, 135.6, 'Transport', 'Car maintenance', 'France', '2024-09-30', 'test'),
(767, 280.75, 'Trips', 'Paris hotel', 'France', '2024-10-05', 'test'),
(768, 125.4, 'Food', 'Parisian cafes', 'France', '2024-10-10', 'test'),
(769, 82.99, 'Entertainment', 'Museum passes', 'France', '2024-10-15', 'test'),
(770, 65.25, 'Transport', 'Metro passes', 'France', '2024-10-20', 'test');

-- --------------------------------------------------------

--
-- Table structure for table `expensescategories`
--

CREATE TABLE `expensescategories` (
  `categoryname` varchar(20) NOT NULL,
  `defaultcolor` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expensescategories`
--

INSERT INTO `expensescategories` (`categoryname`, `defaultcolor`) VALUES
('Bills', '#B8E809'),
('Donations', '#00579E'),
('Entertainment', '#FF0000'),
('Food', '#FFC43B'),
('General', '#5DAEF5'),
('Health', '#FF78B9'),
('Insurance', '#A52A2A'),
('Investments', '#06AD00'),
('Restaurants', '#470124'),
('Services', '#ADD8E6'),
('Transfers', '#000080'),
('Transport', '#006400'),
('Trips', '#40E0D0');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `locationname` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`locationname`) VALUES
('Afghanistan'),
('Albania'),
('Algeria'),
('Andorra'),
('Angola'),
('Antigua and Barbuda'),
('Argentina'),
('Armenia'),
('Australia'),
('Austria'),
('Azerbaijan'),
('Bahrain'),
('Bangladesh'),
('Barbados'),
('Belarus'),
('Belgium'),
('Belize'),
('Benin'),
('Bhutan'),
('Bolivia'),
('Bosnia and Herzegovina'),
('Botswana'),
('Brazil'),
('Brunei'),
('Bulgaria'),
('Burkina Faso'),
('Burundi'),
('Cabo Verde'),
('Cambodia'),
('Cameroon'),
('Canada'),
('Central African Republic'),
('Chad'),
('Chile'),
('China'),
('Colombia'),
('Comoros'),
('Congo, Democratic Republic of the'),
('Congo, Republic of the'),
('Costa Rica'),
('Côte d’Ivoire'),
('Croatia'),
('Cuba'),
('Cyprus'),
('Czech Republic'),
('Denmark'),
('Djibouti'),
('Dominica'),
('Dominican Republic'),
('East Timor (Timor-Leste)'),
('Ecuador'),
('Egypt'),
('El Salvador'),
('Equatorial Guinea'),
('Eritrea'),
('Estonia'),
('Eswatini'),
('Ethiopia'),
('Fiji'),
('Finland'),
('France'),
('Gabon'),
('Georgia'),
('Germany'),
('Ghana'),
('Greece'),
('Grenada'),
('Guatemala'),
('Guinea'),
('Guinea-Bissau'),
('Guyana'),
('Haiti'),
('Honduras'),
('Hungary'),
('Iceland'),
('India'),
('Indonesia'),
('Iran'),
('Iraq'),
('Ireland'),
('Israel'),
('Italy'),
('Jamaica'),
('Japan'),
('Jordan'),
('Kazakhstan'),
('Kenya'),
('Kiribati'),
('Korea, North'),
('Korea, South'),
('Kosovo'),
('Kuwait'),
('Kyrgyzstan'),
('Laos'),
('Latvia'),
('Lebanon'),
('Lesotho'),
('Liberia'),
('Libya'),
('Liechtenstein'),
('Lithuania'),
('Luxembourg'),
('Madagascar'),
('Malawi'),
('Malaysia'),
('Maldives'),
('Mali'),
('Malta'),
('Marshall Islands'),
('Mauritania'),
('Mauritius'),
('Mexico'),
('Micronesia, Federated States of'),
('Moldova'),
('Monaco'),
('Mongolia'),
('Montenegro'),
('Morocco'),
('Mozambique'),
('Myanmar (Burma)'),
('Namibia'),
('Nauru'),
('Nepal'),
('Netherlands'),
('New Zealand'),
('Nicaragua'),
('Niger'),
('Nigeria'),
('North Macedonia'),
('Norway'),
('Oman'),
('Pakistan'),
('Palau'),
('Panama'),
('Papua New Guinea'),
('Paraguay'),
('Peru'),
('Philippines'),
('Poland'),
('Portugal'),
('Qatar'),
('Romania'),
('Russia'),
('Rwanda'),
('Saint Kitts and Nevis'),
('Saint Lucia'),
('Saint Vincent and the Grenadines'),
('Samoa'),
('San Marino'),
('Sao Tome and Principe'),
('Saudi Arabia'),
('Senegal'),
('Serbia'),
('Seychelles'),
('Sierra Leone'),
('Singapore'),
('Slovakia'),
('Slovenia'),
('Solomon Islands'),
('Somalia'),
('South Africa'),
('Spain'),
('Sri Lanka'),
('Sudan'),
('Sudan, South'),
('Suriname'),
('Sweden'),
('Switzerland'),
('Syria'),
('Taiwan'),
('Tajikistan'),
('Tanzania'),
('Thailand'),
('The Bahamas'),
('The Gambia'),
('Togo'),
('Tonga'),
('Trinidad and Tobago'),
('Tunisia'),
('Turkey'),
('Turkmenistan'),
('Tuvalu'),
('Uganda'),
('Ukraine'),
('United Arab Emirates'),
('United Kingdom'),
('United States'),
('Uruguay'),
('Uzbekistan'),
('Vanuatu'),
('Vatican City'),
('Venezuela'),
('Vietnam'),
('Yemen'),
('Zambia'),
('Zimbabwe');

-- --------------------------------------------------------

--
-- Table structure for table `revenues`
--

CREATE TABLE `revenues` (
  `revenueid` int(11) NOT NULL,
  `amount` float NOT NULL,
  `fk_categoryname` varchar(20) NOT NULL,
  `description` varchar(300) NOT NULL,
  `fk_location` varchar(40) NOT NULL,
  `date` date NOT NULL,
  `fk_userid` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `revenues`
--

INSERT INTO `revenues` (`revenueid`, `amount`, `fk_categoryname`, `description`, `fk_location`, `date`, `fk_userid`) VALUES
(1, 3100.25, 'Salary', 'Salary from job', 'United States', '2024-02-01', 'test'),
(2, 2275.8, 'Investments', 'Dividends from investments', 'Iran', '2024-02-10', 'test'),
(3, 1620.5, 'Freelance Work', 'Freelance web design work', 'United Kingdom', '2024-02-18', 'test'),
(4, 2995.75, 'Bonuses', 'Annual bonus from employer', 'United States', '2024-02-25', 'test'),
(5, 3675.2, 'Salary', 'Monthly salary', 'United Kingdom', '2024-03-01', 'test'),
(6, 1499.99, 'Royalties', 'Interest from savings account', 'United States', '2024-03-10', 'test'),
(7, 2130.8, 'Freelance Work', 'Freelance consulting gig', 'Iran', '2024-03-18', 'test'),
(8, 1875.45, 'Rental Income', 'Rental income from property', 'United Kingdom', '2024-03-24', 'test'),
(9, 2585.3, 'Salary', 'Bi-weekly paycheck', 'United States', '2024-03-31', 'test'),
(26, 3500, 'Salary', 'Monthly salary from job', 'Italy', '2024-01-01', 'test'),
(27, 250, 'Freelance Work', 'Web design project payment', 'United States', '2024-01-15', 'test'),
(28, 85.25, 'Dividends', 'Quarterly dividend from investments', 'Italy', '2024-01-31', 'test'),
(29, 3500, 'Salary', 'Monthly salary from job', 'Italy', '2024-02-01', 'test'),
(30, 1200, 'Rental Income', 'Monthly rental income from property', 'Spain', '2024-02-15', 'test'),
(31, 4200, 'Consulting Fees', 'Consulting project payment', 'Germany', '2024-03-10', 'test'),
(32, 3500, 'Salary', 'Monthly salary from job', 'Italy', '2024-03-01', 'test'),
(33, 3500, 'Salary', 'Monthly salary from job', 'Italy', '2024-04-01', 'test'),
(34, 375, 'Affiliate Income', 'Affiliate marketing commissions', 'United Kingdom', '2024-04-20', 'test'),
(35, 95.4, 'Dividends', 'Quarterly dividend from investments', 'France', '2024-04-30', 'test'),
(36, 3500, 'Salary', 'Monthly salary from job', 'Italy', '2024-05-01', 'test'),
(37, 825, 'Freelance Work', 'Freelance writing project', 'France', '2024-05-25', 'test'),
(38, 2800, 'Bonuses', 'Annual bonus from employer', 'Italy', '2024-06-15', 'test'),
(39, 3500, 'Salary', 'Monthly salary from job', 'Italy', '2024-06-01', 'test'),
(40, 3500, 'Salary', 'Monthly salary from job', 'Germany', '2024-07-01', 'test'),
(41, 105.6, 'Dividends', 'Quarterly dividend from investments', 'Switzerland', '2024-07-31', 'test'),
(42, 450, 'Referral Income', 'Referral bonus from program', 'Canada', '2024-08-10', 'test'),
(43, 3500, 'Salary', 'Monthly salary from job', 'Germany', '2024-08-01', 'test'),
(44, 1200, 'Rental Income', 'Monthly rental income from property', 'Greece', '2024-08-15', 'test'),
(45, 3500, 'Salary', 'Monthly salary from job', 'Germany', '2024-09-01', 'test'),
(46, 115.85, 'Dividends', 'Quarterly dividend from investments', 'Netherlands', '2024-09-30', 'test'),
(47, 3500, 'Salary', 'Monthly salary from job', 'Germany', '2024-10-01', 'test'),
(48, 625, 'Consulting Fees', 'Consulting project payment', 'United Kingdom', '2024-10-25', 'test'),
(49, 3500, 'Salary', 'Monthly salary from job', 'Germany', '2024-11-01', 'test'),
(50, 780, 'Capital Gains', 'Profits from investment sale', 'Japan', '2024-11-20', 'test'),
(51, 3500, 'Salary', 'Monthly salary from job', 'Germany', '2024-12-01', 'test'),
(52, 125.5, 'Dividends', 'Quarterly dividend from investments', 'Australia', '2024-12-31', 'test');

-- --------------------------------------------------------

--
-- Table structure for table `revenuescategories`
--

CREATE TABLE `revenuescategories` (
  `categoryname` varchar(20) NOT NULL,
  `defaultcolor` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `revenuescategories`
--

INSERT INTO `revenuescategories` (`categoryname`, `defaultcolor`) VALUES
('Affiliate Income', '#F7CA9E'),
('Bonuses', '#9ED3F7'),
('Capital Gains', '#D7E89E'),
('Commission', '#E89EA3'),
('Consulting Fees', '#A3E89E'),
('Dividends', '#E8D49E'),
('Freelance Work', '#9ED9F7'),
('Grants', '#D2E89E'),
('Interest', '#E8B89E'),
('Investments', '#B0E89E'),
('Licensing Fees', '#E8C09E'),
('Performance Fees', '#9EE8C6'),
('Referral Income', '#F7C79E'),
('Rental Income', '#9EE8D2'),
('Royalties', '#E8A39E'),
('Salary', '#9EE2E8');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userid` varchar(10) NOT NULL,
  `hashcode` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userid`, `hashcode`) VALUES
('test', '$2y$12$/YoPZc8SK.A6BE54qwv7p.4lWynROkpIM8hUcxAr3SqBjVU4IJcMa');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`expenseid`),
  ADD KEY `fk_location` (`fk_location`),
  ADD KEY `fk_userid` (`fk_userid`),
  ADD KEY `fk_categoryname` (`fk_categoryname`);

--
-- Indexes for table `expensescategories`
--
ALTER TABLE `expensescategories`
  ADD PRIMARY KEY (`categoryname`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`locationname`);

--
-- Indexes for table `revenues`
--
ALTER TABLE `revenues`
  ADD PRIMARY KEY (`revenueid`),
  ADD KEY `fk_userid` (`fk_userid`),
  ADD KEY `fk_categoryname` (`fk_categoryname`),
  ADD KEY `fk_location` (`fk_location`);

--
-- Indexes for table `revenuescategories`
--
ALTER TABLE `revenuescategories`
  ADD PRIMARY KEY (`categoryname`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`),
  ADD UNIQUE KEY `hashcode` (`hashcode`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `expenseid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=782;

--
-- AUTO_INCREMENT for table `revenues`
--
ALTER TABLE `revenues`
  MODIFY `revenueid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_ibfk_1` FOREIGN KEY (`fk_categoryname`) REFERENCES `expensescategories` (`categoryname`) ON UPDATE CASCADE,
  ADD CONSTRAINT `expenses_ibfk_2` FOREIGN KEY (`fk_location`) REFERENCES `locations` (`locationname`) ON UPDATE CASCADE,
  ADD CONSTRAINT `expenses_ibfk_3` FOREIGN KEY (`fk_userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `revenues`
--
ALTER TABLE `revenues`
  ADD CONSTRAINT `revenues_ibfk_2` FOREIGN KEY (`fk_location`) REFERENCES `locations` (`locationname`) ON UPDATE CASCADE,
  ADD CONSTRAINT `revenues_ibfk_3` FOREIGN KEY (`fk_categoryname`) REFERENCES `revenuescategories` (`categoryname`) ON UPDATE CASCADE,
  ADD CONSTRAINT `revenues_ibfk_4` FOREIGN KEY (`fk_userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
