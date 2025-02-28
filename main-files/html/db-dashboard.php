<?php
include("config/database.php");
function getUserConversations($userId) {
    global $conn;
    
    $sql = "SELECT DISTINCT 
                IF(sender_id = ?, receiver_id, sender_id) as user_id,
                (SELECT CONCAT(first_name, ' ', last_name) FROM users WHERE id = IF(sender_id = ?, receiver_id, sender_id)) as user_name,
                (SELECT MAX(created_at) FROM messages 
                 WHERE (sender_id = ? AND receiver_id = IF(sender_id = ?, receiver_id, sender_id))
                    OR (receiver_id = ? AND sender_id = IF(sender_id = ?, receiver_id, sender_id))) as last_message_time
            FROM messages 
            WHERE sender_id = ? OR receiver_id = ?
            ORDER BY last_message_time DESC";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiiiiiii", $userId, $userId, $userId, $userId, $userId, $userId, $userId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $conversations = [];
    while ($row = $result->fetch_assoc()) {
        $conversations[] = $row;
    }
    
    return $conversations;
}

function getMessages($senderId, $receiverId) {
    global $conn;
    
    $sql = "SELECT m.*, u.first_name, u.last_name
            FROM messages m
            JOIN users u ON m.sender_id = u.id
            WHERE (m.sender_id = ? AND m.receiver_id = ?)
               OR (m.sender_id = ? AND m.receiver_id = ?)
            ORDER BY m.created_at ASC";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $senderId, $receiverId, $receiverId, $senderId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
    
    return $messages;
}

function getAllUsers($currentUserId) {
    global $conn;
    
    $sql = "SELECT id, CONCAT(first_name, ' ', last_name) as name FROM users WHERE id != ? ORDER BY first_name";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $currentUserId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    
    return $users;
}

$currentUserId = $_SESSION['user_id'] ?? 1;
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="css/vendors.css">
  <link rel="stylesheet" href="css/main.css">

  <title>GoTrip</title>
</head>

<body data-barba="wrapper">


  <div class="preloader js-preloader">
    <div class="preloader__wrap">
      <div class="preloader__icon">
        <svg width="38" height="37" viewBox="0 0 38 37" fill="none" xmlns="http://www.w3.org/2000/svg">
          <g clip-path="url(#clip0_1_41)">
            <path d="M32.9675 13.9422C32.9675 6.25436 26.7129 0 19.0251 0C11.3372 0 5.08289 6.25436 5.08289 13.9422C5.08289 17.1322 7.32025 21.6568 11.7327 27.3906C13.0538 29.1071 14.3656 30.6662 15.4621 31.9166V35.8212C15.4621 36.4279 15.9539 36.92 16.561 36.92H21.4895C22.0965 36.92 22.5883 36.4279 22.5883 35.8212V31.9166C23.6849 30.6662 24.9966 29.1071 26.3177 27.3906C30.7302 21.6568 32.9675 17.1322 32.9675 13.9422V13.9422ZM30.7699 13.9422C30.7699 16.9956 27.9286 21.6204 24.8175 25.7245H23.4375C25.1039 20.7174 25.9484 16.7575 25.9484 13.9422C25.9484 10.3587 25.3079 6.97207 24.1445 4.40684C23.9229 3.91841 23.6857 3.46886 23.4347 3.05761C27.732 4.80457 30.7699 9.02494 30.7699 13.9422ZM20.3906 34.7224H17.6598V32.5991H20.3906V34.7224ZM21.0007 30.4014H17.0587C16.4167 29.6679 15.7024 28.8305 14.9602 27.9224H16.1398C16.1429 27.9224 16.146 27.9227 16.1489 27.9227C16.152 27.9227 23.0902 27.9224 23.0902 27.9224C22.3725 28.8049 21.6658 29.6398 21.0007 30.4014ZM19.0251 2.19765C20.1084 2.19765 21.2447 3.33365 22.1429 5.3144C23.1798 7.60078 23.7508 10.6649 23.7508 13.9422C23.7508 16.6099 22.8415 20.6748 21.1185 25.7245H16.9322C15.2086 20.6743 14.2994 16.6108 14.2994 13.9422C14.2994 10.6649 14.8706 7.60078 15.9075 5.3144C16.8057 3.33365 17.942 2.19765 19.0251 2.19765V2.19765ZM7.28053 13.9422C7.28053 9.02494 10.3184 4.80457 14.6157 3.05761C14.3647 3.46886 14.1273 3.91841 13.9059 4.40684C12.7425 6.97207 12.102 10.3587 12.102 13.9422C12.102 16.7584 12.9462 20.7176 14.6126 25.7245H13.2259C9.33565 20.6126 7.28053 16.5429 7.28053 13.9422Z" fill="#3554D1" />
          </g>

          <defs>
            <clipPath id="clip0_1_41">
              <rect width="36.92" height="36.92" fill="white" transform="translate(0.540039)" />
            </clipPath>
          </defs>
        </svg>
      </div>
    </div>

    <div class="preloader__title">GoTrip</div>
  </div>


  <div class="header-margin"></div>
  <header data-add-bg="" class="header -dashboard bg-white js-header" data-x="header" data-x-toggle="is-menu-opened">
    <div data-anim="fade" class="header__container px-30 sm:px-20">
      <div class="-left-side">
        <a href="index.html" class="header-logo" data-x="header-logo" data-x-toggle="is-logo-dark">
          <img src="img/general/logo-dark.svg" alt="logo icon">
          <img src="img/general/logo-dark.svg" alt="logo icon">
        </a>
      </div>

      <div class="row justify-between items-center pl-60 lg:pl-20">
        <div class="col-auto">
          <div class="d-flex items-center">
            <button data-x-click="dashboard">
              <i class="icon-menu-2 text-20"></i>
            </button>

            <div class="single-field relative d-flex items-center md:d-none ml-30">
              <input class="pl-50 border-light text-dark-1 h-50 rounded-8" type="email" placeholder="Search">
              <button class="absolute d-flex items-center h-full">
                <i class="icon-search text-20 px-15 text-dark-1"></i>
              </button>
            </div>
          </div>
        </div>

        <div class="col-auto">
          <div class="d-flex items-center">

            <div class="header-menu " data-x="mobile-menu" data-x-toggle="is-menu-active">
              <div class="mobile-overlay"></div>

              <div class="header-menu__content">
                <div class="mobile-bg js-mobile-bg"></div>

                <div class="menu js-navList">
                  <ul class="menu__nav text-dark-1 fw-500 -is-active">

                    <li >
                      <a href="index.php">
                        <span class="mr-10">Home</span>
                      </a>
                    <li>
                      <a data-barba href="db-dashboard.php">
                        <span class="mr-10">Dashboard</span>
                        
                      </a>
                  </ul>
                </div>

                <div class="mobile-footer px-20 py-20 border-top-light js-mobile-footer">
                </div>
              </div>
            </div>


            <div class="row items-center x-gap-5 y-gap-20 pl-20 lg:d-none">
            <div class="messaging-modal" id="messagingModal" style="display: none;">
                <div class="messaging-container">
                    <div class="messaging-header">
                        <h3>Messages</h3>
                        <button id="closeMessagingModal" class="close-modal">×</button>
                    </div>
                    
                    <div class="messaging-sidebar">
                        <div class="new-message-btn">
                            <button id="newMessageBtn" class="button -dark-1 py-15 px-35 h-60 col-12 rounded-4 bg-blue-1 text-white">
                                <i class="icon-plus text-14 mr-10"></i>
                                New Message
                            </button>
                        </div>
                        
                        <div class="conversation-list" id="conversationList">
                            
                        </div>
                    </div>
                    
                    <div class="message-container">
                        <div id="emptyStateMessage" class="empty-state">
                            <i class="icon-email-2 text-60"></i>
                            <p>Select a conversation or start a new one</p>
                        </div>
                        
                        <div id="activeConversation" class="active-conversation" style="display: none;">
                            <div class="conversation-header">
                                <h4 id="conversationRecipient">Recipient Name</h4>
                            </div>
                            
                            <div id="messagesList" class="messages-list">
                                
                            </div>
                            
                            <div class="message-input">
                                <input type="hidden" id="currentRecipientId" value="">
                                <textarea id="messageText" placeholder="Type your message..."></textarea>
                                <button id="sendMessageBtn" class="button -blue-1 py-10 px-20 h-40 rounded-4 bg-blue-1 text-white">
                                    <i class="icon-send text-16"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

              <div class="new-message-modal" id="newMessageModal" style="display: none;">
                  <div class="new-message-container">
                      <div class="new-message-header">
                          <h3>New Message</h3>
                          <button id="closeNewMessageModal" class="close-modal">×</button>
                      </div>
                      
                      <div class="new-message-content">
                          <div class="form-group">
                              <label for="recipientSelect">Select Recipient:</label>
                              <select id="recipientSelect" class="form-select">
                                  <option value="">Select a user...</option>
                                  <?php foreach(getAllUsers($currentUserId) as $user): ?>
                                      <option value="<?php echo $user['id']; ?>"><?php echo htmlspecialchars($user['name']); ?></option>
                                  <?php endforeach; ?>
                              </select>
                          </div>
                          
                          <div class="new-message-actions">
                              <button id="startConversationBtn" class="button -blue-1 py-15 px-35 h-60 rounded-4 bg-blue-1 text-white">
                                  Start Conversation
                              </button>
                          </div>
                      </div>
                  </div>
              </div>
              <style>
                .messaging-modal {
                  position: fixed;
                  top: 0;
                  left: 0;
                  width: 100%;
                  height: 100%;
                  background-color: rgba(0, 0, 0, 0.5);
                  z-index: 1000;
                  display: flex;
                  justify-content: center;
                  align-items: center;
                }

                .messaging-container {
                  width: 900px;
                  height: 600px;
                  background-color: white;
                  border-radius: 8px;
                  overflow: hidden;
                  display: flex;
                }

                .messaging-header {
                  padding: 15px 20px;
                  background-color: #3554D1;
                  color: white;
                  display: flex;
                  justify-content: space-between;
                  align-items: center;
                }

                .messaging-header h3 {
                  margin: 0;
                  color: white;
                }

                .close-modal {
                  background: none;
                  border: none;
                  color: white;
                  font-size: 24px;
                  cursor: pointer;
                }

                .messaging-body {
                  display: flex;
                  flex: 1;
                  overflow: hidden;
                }

                .messaging-sidebar {
                  width: 280px;
                  background-color: #f5f5f5;
                  border-right: 1px solid #e0e0e0;
                  display: flex;
                  flex-direction: column;
                  height: 100%;
                }

                .new-message-btn {
                  margin-bottom: 15px;
                  border-bottom: 1px solid #e0e0e0;
                }

                .conversation-item {
                  padding: 15px;
                  border-bottom: 1px solid #e0e0e0;
                  cursor: pointer;
                  transition: background-color 0.2s;
                }

                .conversation-item:hover {
                  background-color: #eaeaea;
                }

                .conversation-item.active {
                  background-color: #e6f2ff;
                }

                .conversation-name {
                  font-weight: 500;
                  margin-bottom: 5px;
                }

                .conversation-time {
                  font-size: 12px;
                  color: #777;
                }

                .message-container {
                  flex: 1;
                  display: flex;
                  flex-direction: column;
                  height: 100%;
                }
                
                .empty-state {
                  display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                height: 100%;
                }

                .active-conversation {
                  display: flex;
                  flex-direction: column;
                  height: 100%;
                }

                .conversation-header {
                  padding: 15px 20px;
                  background-color: #f9f9f9;
                  border-bottom: 1px solid #e0e0e0;
                }

                .conversation-header h4 {
                  margin: 0;
                }

                .conversation-list {
                  flex: 1;
                  overflow-y: auto;
                  max-height: calc(100% - 70px);
                }

                .messages-list {
                  flex: 1;
                  padding: 20px;
                  overflow-y: auto;
                  display: flex;
                  flex-direction: column;
                  max-height: calc(100% - 130px);
                }

                .message {
                  max-width: 70%;
                  padding: 10px 15px;
                  border-radius: 18px;
                  margin-bottom: 10px;
                  position: relative;
                }

                .message-sent {
                  align-self: flex-end;
                  background-color: #3554D1;
                  color: white;
                  border-bottom-right-radius: 5px;
                }

                .message-received {
                  align-self: flex-start;
                  background-color: #f0f0f0;
                  color: #333;
                  border-bottom-left-radius: 5px;
                }

                .message-sender {
                  font-size: 12px;
                  margin-bottom: 3px;
                  font-weight: 500;
                }

                .message-time {
                  font-size: 10px;
                  position: absolute;
                  bottom: -16px;
                  color: #777;
                }

                .message-sent .message-time {
                  right: 5px;
                }

                .message-received .message-time {
                  left: 5px;
                }

                .message-input {
                  padding: 15px;
                  border-top: 1px solid #e0e0e0;
                  display: flex;
                  align-items: center;
                  background-color: white;
                }

                .message-input textarea {
                  flex: 1;
                  border: 1px solid #ddd;
                  border-radius: 20px;
                  padding: 10px 15px;
                  resize: none;
                  height: 40px;
                  margin-right: 10px;
                }

                .new-message-modal {
                  position: fixed;
                  top: 0;
                  left: 0;
                  width: 100%;
                  height: 100%;
                  background-color: rgba(0, 0, 0, 0.5);
                  z-index: 1100;
                  display: flex;
                  justify-content: center;
                  align-items: center;
                }

                .new-message-container {
                  width: 400px;
                  background-color: white;
                  border-radius: 8px;
                  overflow: hidden;
                }

                .new-message-header {
                  padding: 15px 20px;
                  background-color: #3554D1;
                  color: white;
                  display: flex;
                  justify-content: space-between;
                  align-items: center;
                }

                .new-message-content {
                  padding: 20px;
                }

                .form-group {
                  margin-bottom: 20px;
                }

                .form-group label {
                  display: block;
                  margin-bottom: 5px;
                  font-weight: 500;
                }

                .form-select {
                  width: 100%;
                  padding: 10px;
                  border: 1px solid #ddd;
                  border-radius: 4px;
                }

                .new-message-actions {
                  text-align: right;
                }
              </style>

              <div class="col-auto">
                <button class="button -blue-1-05 size-50 rounded-22 flex-center">
                  <i class="icon-notification text-20"></i>
                </button>
              </div>
            </div>

            <div class="pl-15">
              <img src="img/avatars/3.png" alt="image" class="size-50 rounded-22 object-cover">
            </div>

            <div class="d-none xl:d-flex x-gap-20 items-center pl-20" data-x="header-mobile-icons" data-x-toggle="text-white">
              <div><button class="d-flex items-center icon-menu text-20" data-x-click="html, header, header-logo, header-mobile-icons, mobile-menu"></button></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>


  <div class="dashboard" data-x="dashboard" data-x-toggle="-is-sidebar-open">
    <div class="dashboard__sidebar bg-white scroll-bar-1">


      <div class="sidebar -dashboard">

        <div class="sidebar__item">
          <div class="sidebar__button -is-active">
            <a href="db-dashboard.html" class="d-flex items-center text-15 lh-1 fw-500">
              <img src="img/dashboard/sidebar/compass.svg" alt="image" class="mr-15">
              Dashboard
            </a>
          </div>
        </div>

        <div class="sidebar__item">
          <div class="sidebar__button ">
            <a href="db-booking.php" class="d-flex items-center text-15 lh-1 fw-500">
              <img src="img/dashboard/sidebar/booking.svg" alt="image" class="mr-15">
              Booking History
            </a>
          </div>
        </div>

        <div class="sidebar__item">
          <div class="sidebar__button ">
            <a href="db-wishlist.php" class="d-flex items-center text-15 lh-1 fw-500">
              <img src="img/dashboard/sidebar/bookmark.svg" alt="image" class="mr-15">
              Wishlist
            </a>
          </div>
        </div>

        <div class="sidebar__item">
          <div class="sidebar__button ">
            <a href="create-studio.php" class="d-flex items-center text-15 lh-1 fw-500">
              <img src="img/dashboard/sidebar/hotel.svg" alt="image" class="mr-15">
              Gestion Studio
            </a>
          </div>
        </div>

        <div class="sidebar__item">
          <div class="sidebar__button ">
            <a href="db-settings.php" class="d-flex items-center text-15 lh-1 fw-500">
              <img src="img/dashboard/sidebar/gear.svg" alt="image" class="mr-15">
              Settings
            </a>
          </div>
        </div>

        <div class="sidebar__item">
          <div class="sidebar__button ">
            <a href="index.php" class="d-flex items-center text-15 lh-1 fw-500">
              <img src="img/dashboard/sidebar/log-out.svg" alt="image" class="mr-15">
              Logout
            </a>
          </div>
        </div>

      </div>


    </div>

    <div class="dashboard__main">
      <div class="dashboard__content bg-light-2">
        <div class="row y-gap-20 justify-between items-end pb-60 lg:pb-40 md:pb-32">
          <div class="col-auto">

            <h1 class="text-30 lh-14 fw-600">Dashboard</h1>
            <div class="text-15 text-light-1">Lorem ipsum dolor sit amet, consectetur.</div>

          </div>

          <div class="col-auto">

          </div>
        </div>


        <div class="row y-gap-30">

          <div class="col-xl-3 col-md-6">
            <div class="py-30 px-30 rounded-4 bg-white shadow-3">
              <div class="row y-gap-20 justify-between items-center">
                <div class="col-auto">
                  <div class="fw-500 lh-14">Pending</div>
                  <div class="text-26 lh-16 fw-600 mt-5">$12,800</div>
                  <div class="text-15 lh-14 text-light-1 mt-5">Total pending</div>
                </div>

                <div class="col-auto">
                  <img src="img/dashboard/icons/1.svg" alt="icon">
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-3 col-md-6">
            <div class="py-30 px-30 rounded-4 bg-white shadow-3">
              <div class="row y-gap-20 justify-between items-center">
                <div class="col-auto">
                  <div class="fw-500 lh-14">Earnings</div>
                  <div class="text-26 lh-16 fw-600 mt-5">$14,200</div>
                  <div class="text-15 lh-14 text-light-1 mt-5">Total earnings</div>
                </div>

                <div class="col-auto">
                  <img src="img/dashboard/icons/2.svg" alt="icon">
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-3 col-md-6">
            <div class="py-30 px-30 rounded-4 bg-white shadow-3">
              <div class="row y-gap-20 justify-between items-center">
                <div class="col-auto">
                  <div class="fw-500 lh-14">Bookings</div>
                  <div class="text-26 lh-16 fw-600 mt-5">$8,100</div>
                  <div class="text-15 lh-14 text-light-1 mt-5">Total bookings</div>
                </div>

                <div class="col-auto">
                  <img src="img/dashboard/icons/3.svg" alt="icon">
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-3 col-md-6">
            <div class="py-30 px-30 rounded-4 bg-white shadow-3">
              <div class="row y-gap-20 justify-between items-center">
                <div class="col-auto">
                  <div class="fw-500 lh-14">Services</div>
                  <div class="text-26 lh-16 fw-600 mt-5">22,786</div>
                  <div class="text-15 lh-14 text-light-1 mt-5">Total bookable services</div>
                </div>

                <div class="col-auto">
                  <img src="img/dashboard/icons/4.svg" alt="icon">
                </div>
              </div>
            </div>
          </div>

        </div>

        <div class="row y-gap-30 pt-20">
          <div class="col-xl-7 col-md-6">
            <div class="py-30 px-30 rounded-4 bg-white shadow-3">
              <div class="d-flex justify-between items-center">
                <h2 class="text-18 lh-1 fw-500">
                  Earning Statistics
                </h2>


                <div class="dropdown js-dropdown js-category-active">
                  <div class="dropdown__button d-flex items-center bg-white border-light rounded-100 px-15 py-10 text-14 lh-12" data-el-toggle=".js-category-toggle" data-el-toggle-active=".js-category-active">
                    <span class="js-dropdown-title">This Week</span>
                    <i class="icon icon-chevron-sm-down text-7 ml-10"></i>
                  </div>

                  <div class="toggle-element -dropdown  js-click-dropdown js-category-toggle">
                    <div class="text-14 y-gap-15 js-dropdown-list">

                      <div><a href="#" class="d-block js-dropdown-link">Animation</a></div>

                      <div><a href="#" class="d-block js-dropdown-link">Design</a></div>

                      <div><a href="#" class="d-block js-dropdown-link">Illustration</a></div>

                      <div><a href="#" class="d-block js-dropdown-link">Business</a></div>

                    </div>
                  </div>
                </div>

              </div>

              <div class="pt-30">
                <canvas id="lineChart"></canvas>
              </div>
            </div>
          </div>

          <div class="col-xl-5 col-md-6">
            <div class="py-30 px-30 rounded-4 bg-white shadow-3">
              <div class="d-flex justify-between items-center">
                <h2 class="text-18 lh-1 fw-500">
                  Recent Bookings
                </h2>

                <div class="">
                  <a href="#" class="text-14 text-blue-1 fw-500 underline">View All</a>
                </div>
              </div>

              <div class="overflow-scroll scroll-bar-1 pt-30">
                <table class="table-2 col-12">
                  <thead class="">
                    <tr>
                      <th>#</th>
                      <th>Item</th>
                      <th>Total</th>
                      <th>Paid</th>
                      <th>Status</th>
                      <th>Created At</th>
                    </tr>
                  </thead>
                  <tbody>

                    <tr>
                      <td>#1</td>
                      <td>New York<br> Discover America</td>
                      <td class="fw-500">$130</td>
                      <td>$0</td>
                      <td>
                        <div class="rounded-100 py-4 text-center col-12 text-14 fw-500 bg-yellow-4 text-yellow-3">Pending</div>
                      </td>
                      <td>04/04/2022<br>08:16</td>
                    </tr>

                    <tr>
                      <td>#2</td>
                      <td>New York<br> Discover America</td>
                      <td class="fw-500">$130</td>
                      <td>$0</td>
                      <td>
                        <div class="rounded-100 py-4 text-center col-12 text-14 fw-500 bg-blue-1-05 text-blue-1">Confirmed</div>
                      </td>
                      <td>04/04/2022<br>08:16</td>
                    </tr>

                    <tr>
                      <td>#3</td>
                      <td>New York<br> Discover America</td>
                      <td class="fw-500">$130</td>
                      <td>$0</td>
                      <td>
                        <div class="rounded-100 py-4 text-center col-12 text-14 fw-500 bg-red-3 text-red-2">Rejected</div>
                      </td>
                      <td>04/04/2022<br>08:16</td>
                    </tr>

                    <tr>
                      <td>#4</td>
                      <td>New York<br> Discover America</td>
                      <td class="fw-500">$130</td>
                      <td>$0</td>
                      <td>
                        <div class="rounded-100 py-4 text-center col-12 text-14 fw-500 bg-blue-1-05 text-blue-1">Confirmed</div>
                      </td>
                      <td>04/04/2022<br>08:16</td>
                    </tr>

                    <tr>
                      <td>#5</td>
                      <td>New York<br> Discover America</td>
                      <td class="fw-500">$130</td>
                      <td>$0</td>
                      <td>
                        <div class="rounded-100 py-4 text-center col-12 text-14 fw-500 bg-blue-1-05 text-blue-1">Confirmed</div>
                      </td>
                      <td>04/04/2022<br>08:16</td>
                    </tr>

                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>


        <footer class="footer -dashboard mt-60">
          <div class="footer__row row y-gap-10 items-center justify-between">
            <div class="col-auto">
              <div class="row y-gap-20 items-center">
                <div class="col-auto">
                  <div class="text-14 lh-14 mr-30">© 2022 GoTrip LLC All rights reserved.</div>
                </div>

                <div class="col-auto">
                  <div class="row x-gap-20 y-gap-10 items-center text-14">
                    <div class="col-auto">
                      <a href="#" class="text-13 lh-1">Privacy</a>
                    </div>
                    <div class="col-auto">
                      <a href="#" class="text-13 lh-1">Terms</a>
                    </div>
                    <div class="col-auto">
                      <a href="#" class="text-13 lh-1">Site Map</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-auto">
              <div class="d-flex x-gap-5 y-gap-5 items-center">
                <button class="text-14 fw-500 underline">English (US)</button>
                <button class="text-14 fw-500 underline">USD</button>
              </div>
            </div>
          </div>
        </footer>
      </div>
    </div>
  </div>

  <!-- JavaScript -->

  <script>
    document.addEventListener('DOMContentLoaded', function() {
        const messageButton = document.querySelector('.button.-blue-1-05.size-50');
        const messagingModal = document.getElementById('messagingModal');
        const closeMessagingModal = document.getElementById('closeMessagingModal');
        const newMessageBtn = document.getElementById('newMessageBtn');
        const newMessageModal = document.getElementById('newMessageModal');
        const closeNewMessageModal = document.getElementById('closeNewMessageModal');
        const startConversationBtn = document.getElementById('startConversationBtn');
        const recipientSelect = document.getElementById('recipientSelect');
        const emptyStateMessage = document.getElementById('emptyStateMessage');
        const activeConversation = document.getElementById('activeConversation');
        const conversationList = document.getElementById('conversationList');
        const messagesList = document.getElementById('messagesList');
        const messageText = document.getElementById('messageText');
        const sendMessageBtn = document.getElementById('sendMessageBtn');
        const currentRecipientId = document.getElementById('currentRecipientId');
        const conversationRecipient = document.getElementById('conversationRecipient');
        
        const currentUserId = <?php echo $currentUserId; ?>;
        
        messageButton.addEventListener('click', function(e) {
            e.preventDefault();
            messagingModal.style.display = 'flex';
            loadConversations();
        });
        
        closeMessagingModal.addEventListener('click', function() {
            messagingModal.style.display = 'none';
        });
        
        newMessageBtn.addEventListener('click', function() {
            newMessageModal.style.display = 'flex';
        });
        
        closeNewMessageModal.addEventListener('click', function() {
            newMessageModal.style.display = 'none';
        });
        
        startConversationBtn.addEventListener('click', function() {
            const recipientId = recipientSelect.value;
            if (!recipientId) {
                alert('Please select a recipient');
                return;
            }
            
            const recipientName = recipientSelect.options[recipientSelect.selectedIndex].text;
            openConversation(recipientId, recipientName);
            newMessageModal.style.display = 'none';
        });
        
        sendMessageBtn.addEventListener('click', sendMessage);
        
        messageText.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });
        
        function loadConversations() {
            fetch('api/get-conversations.php')
                .then(response => response.json())
                .then(data => {
                    conversationList.innerHTML = '';
                    
                    if (data.length === 0) {
                        const emptyItem = document.createElement('div');
                        emptyItem.className = 'conversation-item';
                        emptyItem.innerHTML = '<p>No conversations yet</p>';
                        conversationList.appendChild(emptyItem);
                        return;
                    }
                    
                    data.forEach(conversation => {
                        const conversationItem = document.createElement('div');
                        conversationItem.className = 'conversation-item';
                        conversationItem.dataset.userId = conversation.user_id;
                        conversationItem.dataset.userName = conversation.user_name;
                        
                        const date = new Date(conversation.last_message_time);
                        const formattedDate = date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                        
                        conversationItem.innerHTML = `
                            <div class="conversation-name">${conversation.user_name}</div>
                            <div class="conversation-time">${formattedDate}</div>
                        `;
                        
                        conversationItem.addEventListener('click', function() {
                            const userId = this.dataset.userId;
                            const userName = this.dataset.userName;
                            openConversation(userId, userName);
                        });
                        
                        conversationList.appendChild(conversationItem);
                    });
                })
                .catch(error => {
                    console.error('Error loading conversations:', error);
                });
        }
        
        function openConversation(userId, userName) {
            emptyStateMessage.style.display = 'none';
            activeConversation.style.display = 'flex';
            conversationRecipient.textContent = userName;
            currentRecipientId.value = userId;
            
            const conversationItems = document.querySelectorAll('.conversation-item');
            conversationItems.forEach(item => {
                item.classList.remove('active');
                if (item.dataset.userId === userId) {
                    item.classList.add('active');
                }
            });
            
            loadMessages(userId);
        }
        
        function loadMessages(recipientId) {
            fetch(`api/get-messages.php?recipient_id=${recipientId}`)
                .then(response => response.json())
                .then(data => {
                    messagesList.innerHTML = '';
                    
                    data.forEach(message => {
                        const messageItem = document.createElement('div');
                        messageItem.className = message.sender_id == currentUserId ? 'message message-sent' : 'message message-received';
                        
                        const date = new Date(message.created_at);
                        const formattedDate = date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                        
                        let senderName = '';
                        if (message.sender_id != currentUserId) {
                            senderName = `<div class="message-sender">${message.first_name} ${message.last_name}</div>`;
                        }
                        
                        messageItem.innerHTML = `
                            ${senderName}
                            <div class="message-content">${message.message}</div>
                            <div class="message-time">${formattedDate}</div>
                        `;
                        
                        messagesList.appendChild(messageItem);
                    });
                    
                    messagesList.scrollTop = messagesList.scrollHeight;
                })
                .catch(error => {
                    console.error('Error loading messages:', error);
                });
        }
        
        function sendMessage() {
            const message = messageText.value.trim();
            const recipientId = currentRecipientId.value;
            
            if (!message || !recipientId) {
                return;
            }
            
            const formData = new FormData();
            formData.append('recipient_id', recipientId);
            formData.append('message', message);
            
            fetch('api/send-message.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    messageText.value = '';
                    
                    loadMessages(recipientId);
                    
                    loadConversations();
                } else {
                    alert('Error sending message: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error sending message:', error);
            });
        }
    });
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js" integrity="sha512-QSkVNOCYLtj73J4hbmVoOV6KVZuMluZlioC+trLpewV8qMjsWqlIQvkn1KGX2StWvPMdWGBqim1xlC8krl1EKQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAAz77U5XQuEME6TpftaMdX0bBelQxXRlM"></script>
  <script src="https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js"></script>

  <script src="js/vendors.js"></script>
  <script src="js/main.js"></script>
</body>

</html>