<?php
session_start(); // Démarrer la session

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$dsn = 'mysql:dbname=onemix;host=127.0.0.1';
$user = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Activer les exceptions PDO
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

if (count($_POST) > 0) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo "Utilisateur trouvé : " . print_r($user, true); // Affiche les données de l'utilisateur
        if (password_verify($password, $user['password'])) {
            echo "Mot de passe correct !";
            $_SESSION['user'] = $user; // Stocker les informations de l'utilisateur dans la session
            header('Location: index.php'); // Rediriger vers index.php
            exit();
        } else {
            echo "Mot de passe incorrect.";
        }
    } else {
        echo "Aucun utilisateur trouvé avec cet email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en" data-x="html" data-x-toggle="html-overflow-hidden">

<head>
  <!-- Required meta tags -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Google fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600&display=swap" rel="stylesheet">

  <!-- Stylesheets -->
  <link rel="stylesheet" href="css/vendors.css">
  <link rel="stylesheet" href="css/main.css">

  <title>GoTrip</title>
</head>

<body>
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

  <main>


    <div class="header-margin"></div>
    <header data-add-bg="" class="header -dashboard bg-white js-header" data-x="header" data-x-toggle="is-menu-opened">
      <div data-anim="fade" class="header__container px-30 sm:px-20">
        <div class="-left-side">
          <a href="index.php" class="header-logo" data-x="header-logo" data-x-toggle="is-logo-dark">
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
  
                      <li class="menu-item-has-children">
                        <a data-barba href="">
                          <span class="mr-10">Home</span>
                          <i class="icon icon-chevron-sm-down"></i>
                        </a>
  
  
                        <ul class="subnav">
                          <li class="subnav__backBtn js-nav-list-back">
                            <a href="index.php"><i class="icon icon-chevron-sm-down"></i> Home</a>
                          </li>
  
                          <li><a href="index.php">Home</a></li>
                        </ul>
  
                      
  
  
                        
  
                      
  
  
                      <li class="menu-item-has-children">
                        <a data-barba href="">
                          <span class="mr-10">Dashboard</span>
                          <i class="icon icon-chevron-sm-down"></i>
                        </a>
  
  
                        <ul class="subnav">
                          <li class="subnav__backBtn js-nav-list-back">
                            <a href="db-dashboard.html"><i class="icon icon-chevron-sm-down"></i> Dashboard</a>
                          </li>
  
                          <li><a href="db-dashboard.html">Dashboard</a></li>
  
                        </ul>
  
                      
                    </ul>
                  </div>
  
                  <div class="mobile-footer px-20 py-20 border-top-light js-mobile-footer">
                  </div>
                </div>
              </div>
  
  
              <div class="row items-center x-gap-5 y-gap-20 pl-20 lg:d-none">
                <div class="col-auto">
                  <button class="button -blue-1-05 size-50 rounded-22 flex-center">
                    <i class="icon-email-2 text-20"></i>
                  </button>
                </div>
  
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


    <section class="layout-pt-lg layout-pb-lg bg-blue-2">
      <div class="container">
        <div class="row justify-center">
          <div class="col-xl-6 col-lg-7 col-md-9">
            <div class="px-50 py-50 sm:px-20 sm:py-20 bg-white shadow-4 rounded-4">
              <div class="row y-gap-20 mb-3">
                <div class="col-12">
                  <h1 class="text-22 fw-500">Welcome back</h1>
                  <p class="mt-10">Don't have an account yet? <a href="signup.php" class="text-blue-1">Sign up for free</a></p>
                </div>

                <form action="login.php" method="POST">
                  <div class="col-12 mb-3">
                      <div class="form-input">
                          <input type="text" name="email" required>
                          <label class="lh-1 text-14 text-light-1">Email</label>
                      </div>
                  </div>
                  <div class="col-12">
                      <div class="form-input">
                          <input type="password" name="password" required>
                          <label class="lh-1 text-14 text-light-1">Password</label>
                      </div>
                  </div>
                  <div class="col-12">
                      <a href="#" class="text-14 fw-500 text-blue-1 underline">Forgot your password?</a>
                  </div>
                  <div class="col-12">
                      <button type="submit" class="button py-20 -dark-1 bg-blue-1 text-white mx-auto d-block" style="width: 530px;">
                          Sign In <div class="icon-arrow-top-right ml-15"></div>
                      </button>
                  </div>
                </form>

              <div class="row y-gap-20 pt-30">
                  <div class="col-12">
                      <div class="text-center">or sign in with</div>
                      <button class="button col-12 -outline-blue-1 text-blue-1 py-15 rounded-8 mt-10">
                          <i class="icon-apple text-15 mr-10"></i>
                          Facebook
                      </button>
                      <button class="button col-12 -outline-red-1 text-red-1 py-15 rounded-8 mt-15">
                          <i class="icon-apple text-15 mr-10"></i>
                          Google
                      </button>
                      <button class="button col-12 -outline-dark-2 text-dark-2 py-15 rounded-8 mt-15">
                          <i class="icon-apple text-15 mr-10"></i>
                          Apple
                      </button>
                  </div>
              </div>

              <?php if (isset($_SESSION['user'])): ?>
                  <a href="logout.php" class="button px-30 fw-400 text-14 border-white -outline-white h-50 text-white ml-20">Déconnexion</a>
              <?php endif; ?>

                <div class="col-12">
                  <div class="text-center px-30">By creating an account, you agree to our Terms of Service and Privacy Statement.</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="layout-pt-md layout-pb-md bg-dark-2">
      <div class="container">
        <div class="row y-gap-30 justify-between items-center">
          <div class="col-auto">
            <div class="row y-gap-20  flex-wrap items-center">
              <div class="col-auto">
                <div class="icon-newsletter text-60 sm:text-40 text-white"></div>
              </div>

              <div class="col-auto">
                <h4 class="text-26 text-white fw-600">Your Travel Journey Starts Here</h4>
                <div class="text-white">Sign up and we'll send the best deals to you</div>
              </div>
            </div>
          </div>

          <div class="col-auto">
            <div class="single-field -w-410 d-flex x-gap-10 y-gap-20">
              <div>
                <input class="bg-white h-60" type="text" placeholder="Your Email">
              </div>

              <div>
                <button class="button -md h-60 bg-blue-1 text-white">Subscribe</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <footer class="footer -type-1">
      <div class="container">
        <div class="pt-60 pb-60">
          <div class="row y-gap-40 justify-between xl:justify-start">
            <div class="col-xl-2 col-lg-4 col-sm-6">
              <h5 class="text-16 fw-500 mb-30">Contact Us</h5>

              <div class="mt-30">
                <div class="text-14 mt-30">Toll Free Customer Care</div>
                <a href="#" class="text-18 fw-500 text-blue-1 mt-5">+(1) 123 456 7890</a>
              </div>

              <div class="mt-35">
                <div class="text-14 mt-30">Need live support?</div>
                <a href="#" class="text-18 fw-500 text-blue-1 mt-5">hi@gotrip.com</a>
              </div>
            </div>

            <div class="col-xl-2 col-lg-4 col-sm-6">
              <h5 class="text-16 fw-500 mb-30">Company</h5>
              <div class="d-flex y-gap-10 flex-column">
                <a href="#">About Us</a>
                <a href="#">Careers</a>
                <a href="#">Blog</a>
                <a href="#">Press</a>
                <a href="#">Gift Cards</a>
                <a href="#">Magazine</a>
              </div>
            </div>

            <div class="col-xl-2 col-lg-4 col-sm-6">
              <h5 class="text-16 fw-500 mb-30">Support</h5>
              <div class="d-flex y-gap-10 flex-column">
                <a href="#">Contact</a>
                <a href="#">Legal Notice</a>
                <a href="#">Privacy Policy</a>
                <a href="#">Terms and Conditions</a>
                <a href="#">Sitemap</a>
              </div>
            </div>

            <div class="col-xl-2 col-lg-4 col-sm-6">
              <h5 class="text-16 fw-500 mb-30">Other Services</h5>
              <div class="d-flex y-gap-10 flex-column">
                <a href="#">Car hire</a>
                <a href="#">Activity Finder</a>
                <a href="#">Tour List</a>
                <a href="#">Flight finder</a>
                <a href="#">Cruise Ticket</a>
                <a href="#">Holiday Rental</a>
                <a href="#">Travel Agents</a>
              </div>
            </div>

            <div class="col-xl-2 col-lg-4 col-sm-6">
              <h5 class="text-16 fw-500 mb-30">Mobile</h5>

              <div class="d-flex items-center px-20 py-10 rounded-4 border-light">
                <div class="icon-apple text-24"></div>
                <div class="ml-20">
                  <div class="text-14 text-light-1">Download on the</div>
                  <div class="text-15 lh-1 fw-500">Apple Store</div>
                </div>
              </div>

              <div class="d-flex items-center px-20 py-10 rounded-4 border-light mt-20">
                <div class="icon-play-market text-24"></div>
                <div class="ml-20">
                  <div class="text-14 text-light-1">Get in on</div>
                  <div class="text-15 lh-1 fw-500">Google Play</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="py-20 border-top-light">
          <div class="row justify-between items-center y-gap-10">
            <div class="col-auto">
              <div class="row x-gap-30 y-gap-10">
                <div class="col-auto">
                  <div class="d-flex items-center">
                    © 2022 GoTrip LLC All rights reserved.
                  </div>
                </div>

                <div class="col-auto">
                  <div class="d-flex x-gap-15">
                    <a href="#">Privacy</a>
                    <a href="#">Terms</a>
                    <a href="#">Site Map</a>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-auto">
              <div class="row y-gap-10 items-center">
                <div class="col-auto">
                  <div class="d-flex items-center">
                    <button class="d-flex items-center text-14 fw-500 text-dark-1 mr-10">
                      <i class="icon-globe text-16 mr-10"></i>
                      <span class="underline">English (US)</span>
                    </button>

                    <button class="d-flex items-center text-14 fw-500 text-dark-1">
                      <i class="icon-usd text-16 mr-10"></i>
                      <span class="underline">USD</span>
                    </button>
                  </div>
                </div>

                <div class="col-auto">
                  <div class="d-flex x-gap-20 items-center">
                    <a href="#"><i class="icon-facebook text-14"></i></a>
                    <a href="#"><i class="icon-twitter text-14"></i></a>
                    <a href="#"><i class="icon-instagram text-14"></i></a>
                    <a href="#"><i class="icon-linkedin text-14"></i></a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </footer>

  </main>

  <div class="langMenu is-hidden js-langMenu" data-x="lang" data-x-toggle="is-hidden">
    <div class="langMenu__bg" data-x-click="lang"></div>

    <div class="langMenu__content bg-white rounded-4">
      <div class="d-flex items-center justify-between px-30 py-20 sm:px-15 border-bottom-light">
        <div class="text-20 fw-500 lh-15">Select your language</div>
        <button class="pointer" data-x-click="lang">
          <i class="icon-close"></i>
        </button>
      </div>

      <div class="modalGrid px-30 py-30 sm:px-15 sm:py-15">

        <div class="modalGrid__item js-item">
          <div class="py-10 px-15 sm:px-5 sm:py-5">
            <div class="text-15 lh-15 fw-500 text-dark-1">English</div>
            <div class="text-14 lh-15 mt-5 js-title">United States</div>
          </div>
        </div>

        <div class="modalGrid__item js-item">
          <div class="py-10 px-15 sm:px-5 sm:py-5">
            <div class="text-15 lh-15 fw-500 text-dark-1">Türkçe</div>
            <div class="text-14 lh-15 mt-5 js-title">Turkey</div>
          </div>
        </div>

        <div class="modalGrid__item js-item">
          <div class="py-10 px-15 sm:px-5 sm:py-5">
            <div class="text-15 lh-15 fw-500 text-dark-1">Español</div>
            <div class="text-14 lh-15 mt-5 js-title">España</div>
          </div>
        </div>

        <div class="modalGrid__item js-item">
          <div class="py-10 px-15 sm:px-5 sm:py-5">
            <div class="text-15 lh-15 fw-500 text-dark-1">Français</div>
            <div class="text-14 lh-15 mt-5 js-title">France</div>
          </div>
        </div>

        <div class="modalGrid__item js-item">
          <div class="py-10 px-15 sm:px-5 sm:py-5">
            <div class="text-15 lh-15 fw-500 text-dark-1">Italiano</div>
            <div class="text-14 lh-15 mt-5 js-title">Italia</div>
          </div>
        </div>

        <div class="modalGrid__item js-item">
          <div class="py-10 px-15 sm:px-5 sm:py-5">
            <div class="text-15 lh-15 fw-500 text-dark-1">English</div>
            <div class="text-14 lh-15 mt-5 js-title">United States</div>
          </div>
        </div>

        <div class="modalGrid__item js-item">
          <div class="py-10 px-15 sm:px-5 sm:py-5">
            <div class="text-15 lh-15 fw-500 text-dark-1">Türkçe</div>
            <div class="text-14 lh-15 mt-5 js-title">Turkey</div>
          </div>
        </div>

        <div class="modalGrid__item js-item">
          <div class="py-10 px-15 sm:px-5 sm:py-5">
            <div class="text-15 lh-15 fw-500 text-dark-1">Español</div>
            <div class="text-14 lh-15 mt-5 js-title">España</div>
          </div>
        </div>

        <div class="modalGrid__item js-item">
          <div class="py-10 px-15 sm:px-5 sm:py-5">
            <div class="text-15 lh-15 fw-500 text-dark-1">Français</div>
            <div class="text-14 lh-15 mt-5 js-title">France</div>
          </div>
        </div>

        <div class="modalGrid__item js-item">
          <div class="py-10 px-15 sm:px-5 sm:py-5">
            <div class="text-15 lh-15 fw-500 text-dark-1">Italiano</div>
            <div class="text-14 lh-15 mt-5 js-title">Italia</div>
          </div>
        </div>

        <div class="modalGrid__item js-item">
          <div class="py-10 px-15 sm:px-5 sm:py-5">
            <div class="text-15 lh-15 fw-500 text-dark-1">English</div>
            <div class="text-14 lh-15 mt-5 js-title">United States</div>
          </div>
        </div>

        <div class="modalGrid__item js-item">
          <div class="py-10 px-15 sm:px-5 sm:py-5">
            <div class="text-15 lh-15 fw-500 text-dark-1">Türkçe</div>
            <div class="text-14 lh-15 mt-5 js-title">Turkey</div>
          </div>
        </div>

        <div class="modalGrid__item js-item">
          <div class="py-10 px-15 sm:px-5 sm:py-5">
            <div class="text-15 lh-15 fw-500 text-dark-1">Español</div>
            <div class="text-14 lh-15 mt-5 js-title">España</div>
          </div>
        </div>

        <div class="modalGrid__item js-item">
          <div class="py-10 px-15 sm:px-5 sm:py-5">
            <div class="text-15 lh-15 fw-500 text-dark-1">Français</div>
            <div class="text-14 lh-15 mt-5 js-title">France</div>
          </div>
        </div>

        <div class="modalGrid__item js-item">
          <div class="py-10 px-15 sm:px-5 sm:py-5">
            <div class="text-15 lh-15 fw-500 text-dark-1">Italiano</div>
            <div class="text-14 lh-15 mt-5 js-title">Italia</div>
          </div>
        </div>

        <div class="modalGrid__item js-item">
          <div class="py-10 px-15 sm:px-5 sm:py-5">
            <div class="text-15 lh-15 fw-500 text-dark-1">English</div>
            <div class="text-14 lh-15 mt-5 js-title">United States</div>
          </div>
        </div>

        <div class="modalGrid__item js-item">
          <div class="py-10 px-15 sm:px-5 sm:py-5">
            <div class="text-15 lh-15 fw-500 text-dark-1">Türkçe</div>
            <div class="text-14 lh-15 mt-5 js-title">Turkey</div>
          </div>
        </div>

        <div class="modalGrid__item js-item">
          <div class="py-10 px-15 sm:px-5 sm:py-5">
            <div class="text-15 lh-15 fw-500 text-dark-1">Español</div>
            <div class="text-14 lh-15 mt-5 js-title">España</div>
          </div>
        </div>

        <div class="modalGrid__item js-item">
          <div class="py-10 px-15 sm:px-5 sm:py-5">
            <div class="text-15 lh-15 fw-500 text-dark-1">Français</div>
            <div class="text-14 lh-15 mt-5 js-title">France</div>
          </div>
        </div>

        <div class="modalGrid__item js-item">
          <div class="py-10 px-15 sm:px-5 sm:py-5">
            <div class="text-15 lh-15 fw-500 text-dark-1">Italiano</div>
            <div class="text-14 lh-15 mt-5 js-title">Italia</div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <div class="currencyMenu is-hidden js-currencyMenu" data-x="currency" data-x-toggle="is-hidden">
    <div class="currencyMenu__bg" data-x-click="currency"></div>

    <div class="currencyMenu__content bg-white rounded-4">
      <div class="d-flex items-center justify-between px-30 py-20 sm:px-15 border-bottom-light">
        <div class="text-20 fw-500 lh-15">Select your currency</div>
        <button class="pointer" data-x-click="currency">
          <i class="icon-close"></i>
        </button>
      </div>

      <div class="modalGrid px-30 py-30 sm:px-15 sm:py-15">

        <div class="modalGrid__item js-item">
          <div class="py-10 px-15 sm:px-5 sm:py-5">
            <div class="text-15 lh-15 fw-500 text-dark-1">United States dollar</div>
            <div class="text-14 lh-15 mt-5">
              <span class="js-title">USD</span>
              - $
            </div>
          </div>
        </div>

        <div class="modalGrid__item js-item">
          <div class="py-10 px-15 sm:px-5 sm:py-5">
            <div class="text-15 lh-15 fw-500 text-dark-1">Australian dollar</div>
            <div class="text-14 lh-15 mt-5">
              <span class="js-title">AUD</span>
              - $
            </div>
          </div>
        </div>

        <div class="modalGrid__item js-item">
          <div class="py-10 px-15 sm:px-5 sm:py-5">
            <div class="text-15 lh-15 fw-500 text-dark-1">Brazilian real</div>
            <div class="text-14 lh-15 mt-5">
              <span class="js-title">BRL</span>
              - R$
            </div>
          </div>
        </div>

        <div class="modalGrid__item js-item">
          <div class="py-10 px-15 sm:px-5 sm:py-5">
            <div class="text-15 lh-15 fw-500 text-dark-1">Bulgarian lev</div>
            <div class="text-14 lh-15 mt-5">
              <span class="js-title">BGN</span>
              - лв.
            </div>
          </div>
        </div>

        <div class="modalGrid__item js-item">
          <div class="py-10 px-15 sm:px-5 sm:py-5">
            <div class="text-15 lh-15 fw-500 text-dark-1">Canadian dollar</div>
            <div class="text-14 lh-15 mt-5">
              <span class="js-title">CAD</span>
              - $
            </div>
          </div>
        </div>

        <div class="modalGrid__item js-item">
          <div class="py-10 px-15 sm:px-5 sm:py-5">
            <div class="text-15 lh-15 fw-500 text-dark-1">United States dollar</div>
            <div class="text-14 lh-15 mt-5">
              <span class="js-title">USD</span>
              - $
            </div>
          </div>
        </div>

        <div class="modalGrid__item js-item">
          <div class="py-10 px-15 sm:px-5 sm:py-5">
            <div class="text-15 lh-15 fw-500 text-dark-1">Australian dollar</div>
            <div class="text-14 lh-15 mt-5">
              <span class="js-title">AUD</span>
              - $
            </div>
          </div>
        </div>

        <div class="modalGrid__item js-item">
          <div class="py-10 px-15 sm:px-5 sm:py-5">
            <div class="text-15 lh-15 fw-500 text-dark-1">Brazilian real</div>
            <div class="text-14 lh-15 mt-5">
              <span class="js-title">BRL</span>
              - R$
            </div>
          </div>
        </div>

        <div class="modalGrid__item js-item">
          <div class="py-10 px-15 sm:px-5 sm:py-5">
            <div class="text-15 lh-15 fw-500 text-dark-1">Bulgarian lev</div>
            <div class="text-14 lh-15 mt-5">
              <span class="js-title">BGN</span>
              - лв.
            </div>
          </div>
        </div>

        <div class="modalGrid__item js-item">
          <div class="py-10 px-15 sm:px-5 sm:py-5">
            <div class="text-15 lh-15 fw-500 text-dark-1">Canadian dollar</div>
            <div class="text-14 lh-15 mt-5">
              <span class="js-title">CAD</span>
              - $
            </div>
          </div>
        </div>

        <div class="modalGrid__item js-item">
          <div class="py-10 px-15 sm:px-5 sm:py-5">
            <div class="text-15 lh-15 fw-500 text-dark-1">United States dollar</div>
            <div class="text-14 lh-15 mt-5">
              <span class="js-title">USD</span>
              - $
            </div>
          </div>
        </div>

        <div class="modalGrid__item js-item">
          <div class="py-10 px-15 sm:px-5 sm:py-5">
            <div class="text-15 lh-15 fw-500 text-dark-1">Australian dollar</div>
            <div class="text-14 lh-15 mt-5">
              <span class="js-title">AUD</span>
              - $
            </div>
          </div>
        </div>

        <div class="modalGrid__item js-item">
          <div class="py-10 px-15 sm:px-5 sm:py-5">
            <div class="text-15 lh-15 fw-500 text-dark-1">Brazilian real</div>
            <div class="text-14 lh-15 mt-5">
              <span class="js-title">BRL</span>
              - R$
            </div>
          </div>
        </div>

        <div class="modalGrid__item js-item">
          <div class="py-10 px-15 sm:px-5 sm:py-5">
            <div class="text-15 lh-15 fw-500 text-dark-1">Bulgarian lev</div>
            <div class="text-14 lh-15 mt-5">
              <span class="js-title">BGN</span>
              - лв.
            </div>
          </div>
        </div>

        <div class="modalGrid__item js-item">
          <div class="py-10 px-15 sm:px-5 sm:py-5">
            <div class="text-15 lh-15 fw-500 text-dark-1">Canadian dollar</div>
            <div class="text-14 lh-15 mt-5">
              <span class="js-title">CAD</span>
              - $
            </div>
          </div>
        </div>

        <div class="modalGrid__item js-item">
          <div class="py-10 px-15 sm:px-5 sm:py-5">
            <div class="text-15 lh-15 fw-500 text-dark-1">United States dollar</div>
            <div class="text-14 lh-15 mt-5">
              <span class="js-title">USD</span>
              - $
            </div>
          </div>
        </div>

        <div class="modalGrid__item js-item">
          <div class="py-10 px-15 sm:px-5 sm:py-5">
            <div class="text-15 lh-15 fw-500 text-dark-1">Australian dollar</div>
            <div class="text-14 lh-15 mt-5">
              <span class="js-title">AUD</span>
              - $
            </div>
          </div>
        </div>

        <div class="modalGrid__item js-item">
          <div class="py-10 px-15 sm:px-5 sm:py-5">
            <div class="text-15 lh-15 fw-500 text-dark-1">Brazilian real</div>
            <div class="text-14 lh-15 mt-5">
              <span class="js-title">BRL</span>
              - R$
            </div>
          </div>
        </div>

        <div class="modalGrid__item js-item">
          <div class="py-10 px-15 sm:px-5 sm:py-5">
            <div class="text-15 lh-15 fw-500 text-dark-1">Bulgarian lev</div>
            <div class="text-14 lh-15 mt-5">
              <span class="js-title">BGN</span>
              - лв.
            </div>
          </div>
        </div>

        <div class="modalGrid__item js-item">
          <div class="py-10 px-15 sm:px-5 sm:py-5">
            <div class="text-15 lh-15 fw-500 text-dark-1">Canadian dollar</div>
            <div class="text-14 lh-15 mt-5">
              <span class="js-title">CAD</span>
              - $
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <div class="mapFilter" data-x="mapFilter" data-x-toggle="-is-active">
    <div class="mapFilter__overlay"></div>

    <div class="mapFilter__close">
      <button class="button -blue-1 size-40 bg-white shadow-2" data-x-click="mapFilter">
        <i class="icon-close text-15"></i>
      </button>
    </div>

    <div class="mapFilter__grid" data-x="mapFilter__grid" data-x-toggle="-filters-hidden">
      <div class="mapFilter-filter scroll-bar-1">
        <div class="px-20 py-20 md:px-15 md:py-15">
          <div class="d-flex items-center justify-between">
            <div class="text-18 fw-500">Filters</div>

            <button class="size-40 flex-center rounded-full bg-blue-1" data-x-click="mapFilter__grid">
              <i class="icon-chevron-left text-12 text-white"></i>
            </button>
          </div>

          <div class="mapFilter-filter__item">
            <h5 class="text-18 fw-500 mb-10">Popular Filters</h5>
            <div class="sidebar-checkbox">

              <div class="row y-gap-10 items-center justify-between">
                <div class="col-auto">
                  <div class="d-flex items-center">
                    <div class="form-checkbox">
                      <input type="checkbox">
                      <div class="form-checkbox__mark">
                        <div class="form-checkbox__icon icon-check"></div>
                      </div>
                    </div>
                    <div class="text-15 ml-10">Breakfast Included</div>
                  </div>
                </div>

                <div class="col-auto">
                  <div class="text-15 text-light-1">92</div>
                </div>
              </div>

              <div class="row y-gap-10 items-center justify-between">
                <div class="col-auto">
                  <div class="d-flex items-center">
                    <div class="form-checkbox">
                      <input type="checkbox">
                      <div class="form-checkbox__mark">
                        <div class="form-checkbox__icon icon-check"></div>
                      </div>
                    </div>
                    <div class="text-15 ml-10">Romantic</div>
                  </div>
                </div>

                <div class="col-auto">
                  <div class="text-15 text-light-1">45</div>
                </div>
              </div>

              <div class="row y-gap-10 items-center justify-between">
                <div class="col-auto">
                  <div class="d-flex items-center">
                    <div class="form-checkbox">
                      <input type="checkbox">
                      <div class="form-checkbox__mark">
                        <div class="form-checkbox__icon icon-check"></div>
                      </div>
                    </div>
                    <div class="text-15 ml-10">Airport Transfer</div>
                  </div>
                </div>

                <div class="col-auto">
                  <div class="text-15 text-light-1">21</div>
                </div>
              </div>

              <div class="row y-gap-10 items-center justify-between">
                <div class="col-auto">
                  <div class="d-flex items-center">
                    <div class="form-checkbox">
                      <input type="checkbox">
                      <div class="form-checkbox__mark">
                        <div class="form-checkbox__icon icon-check"></div>
                      </div>
                    </div>
                    <div class="text-15 ml-10">WiFi Included </div>
                  </div>
                </div>

                <div class="col-auto">
                  <div class="text-15 text-light-1">78</div>
                </div>
              </div>

              <div class="row y-gap-10 items-center justify-between">
                <div class="col-auto">
                  <div class="d-flex items-center">
                    <div class="form-checkbox">
                      <input type="checkbox">
                      <div class="form-checkbox__mark">
                        <div class="form-checkbox__icon icon-check"></div>
                      </div>
                    </div>
                    <div class="text-15 ml-10">5 Star</div>
                  </div>
                </div>

                <div class="col-auto">
                  <div class="text-15 text-light-1">679</div>
                </div>
              </div>

            </div>
          </div>

          <div class="mapFilter-filter__item">
            <h5 class="text-18 fw-500 mb-10">Nightly Price</h5>
            <div class="row x-gap-10 y-gap-30">
              <div class="col-12">
                <div class="js-price-rangeSlider">
                  <div class="text-14 fw-500"></div>

                  <div class="d-flex justify-between mb-20">
                    <div class="text-15 text-dark-1">
                      <span class="js-lower"></span>
                      -
                      <span class="js-upper"></span>
                    </div>
                  </div>

                  <div class="px-5">
                    <div class="js-slider"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="mapFilter-filter__item">
            <h5 class="text-18 fw-500 mb-10">Amenities</h5>
            <div class="sidebar-checkbox">

              <div class="row y-gap-10 items-center justify-between">
                <div class="col-auto">

                  <div class="d-flex items-center">
                    <div class="form-checkbox ">
                      <input type="checkbox" name="name">
                      <div class="form-checkbox__mark">
                        <div class="form-checkbox__icon icon-check"></div>
                      </div>
                    </div>

                    <div class="text-15 ml-10">Breakfast Included</div>

                  </div>

                </div>

                <div class="col-auto">
                  <div class="text-15 text-light-1">92</div>
                </div>
              </div>

              <div class="row y-gap-10 items-center justify-between">
                <div class="col-auto">

                  <div class="d-flex items-center">
                    <div class="form-checkbox ">
                      <input type="checkbox" name="name">
                      <div class="form-checkbox__mark">
                        <div class="form-checkbox__icon icon-check"></div>
                      </div>
                    </div>

                    <div class="text-15 ml-10">WiFi Included </div>

                  </div>

                </div>

                <div class="col-auto">
                  <div class="text-15 text-light-1">45</div>
                </div>
              </div>

              <div class="row y-gap-10 items-center justify-between">
                <div class="col-auto">

                  <div class="d-flex items-center">
                    <div class="form-checkbox ">
                      <input type="checkbox" name="name">
                      <div class="form-checkbox__mark">
                        <div class="form-checkbox__icon icon-check"></div>
                      </div>
                    </div>

                    <div class="text-15 ml-10">Pool</div>

                  </div>

                </div>

                <div class="col-auto">
                  <div class="text-15 text-light-1">21</div>
                </div>
              </div>

              <div class="row y-gap-10 items-center justify-between">
                <div class="col-auto">

                  <div class="d-flex items-center">
                    <div class="form-checkbox ">
                      <input type="checkbox" name="name">
                      <div class="form-checkbox__mark">
                        <div class="form-checkbox__icon icon-check"></div>
                      </div>
                    </div>

                    <div class="text-15 ml-10">Restaurant </div>

                  </div>

                </div>

                <div class="col-auto">
                  <div class="text-15 text-light-1">78</div>
                </div>
              </div>

              <div class="row y-gap-10 items-center justify-between">
                <div class="col-auto">

                  <div class="d-flex items-center">
                    <div class="form-checkbox ">
                      <input type="checkbox" name="name">
                      <div class="form-checkbox__mark">
                        <div class="form-checkbox__icon icon-check"></div>
                      </div>
                    </div>

                    <div class="text-15 ml-10">Air conditioning </div>

                  </div>

                </div>

                <div class="col-auto">
                  <div class="text-15 text-light-1">679</div>
                </div>
              </div>

            </div>
          </div>

          <div class="mapFilter-filter__item">
            <h5 class="text-18 fw-500 mb-10">Star Rating</h5>
            <div class="row x-gap-10 y-gap-10 pt-10">

              <div class="col-auto">
                <a href="#" class="button -blue-1 bg-blue-1-05 text-blue-1 py-5 px-20 rounded-100">1</a>
              </div>

              <div class="col-auto">
                <a href="#" class="button -blue-1 bg-blue-1-05 text-blue-1 py-5 px-20 rounded-100">2</a>
              </div>

              <div class="col-auto">
                <a href="#" class="button -blue-1 bg-blue-1-05 text-blue-1 py-5 px-20 rounded-100">3</a>
              </div>

              <div class="col-auto">
                <a href="#" class="button -blue-1 bg-blue-1-05 text-blue-1 py-5 px-20 rounded-100">4</a>
              </div>

              <div class="col-auto">
                <a href="#" class="button -blue-1 bg-blue-1-05 text-blue-1 py-5 px-20 rounded-100">5</a>
              </div>

            </div>
          </div>

          <div class="mapFilter-filter__item">
            <h5 class="text-18 fw-500 mb-10">Guest Rating</h5>
            <div class="sidebar-checkbox">

              <div class="row y-gap-10 items-center justify-between">
                <div class="col-auto">

                  <div class="form-radio d-flex items-center ">
                    <div class="radio">
                      <input type="radio" name="name">
                      <div class="radio__mark">
                        <div class="radio__icon"></div>
                      </div>
                    </div>
                    <div class="ml-10">Any</div>
                  </div>

                </div>

                <div class="col-auto">
                  <div class="text-15 text-light-1">92</div>
                </div>
              </div>

              <div class="row y-gap-10 items-center justify-between">
                <div class="col-auto">

                  <div class="form-radio d-flex items-center ">
                    <div class="radio">
                      <input type="radio" name="name">
                      <div class="radio__mark">
                        <div class="radio__icon"></div>
                      </div>
                    </div>
                    <div class="ml-10">Wonderful 4.5+</div>
                  </div>

                </div>

                <div class="col-auto">
                  <div class="text-15 text-light-1">45</div>
                </div>
              </div>

              <div class="row y-gap-10 items-center justify-between">
                <div class="col-auto">

                  <div class="form-radio d-flex items-center ">
                    <div class="radio">
                      <input type="radio" name="name">
                      <div class="radio__mark">
                        <div class="radio__icon"></div>
                      </div>
                    </div>
                    <div class="ml-10">Very good 4+</div>
                  </div>

                </div>

                <div class="col-auto">
                  <div class="text-15 text-light-1">21</div>
                </div>
              </div>

              <div class="row y-gap-10 items-center justify-between">
                <div class="col-auto">

                  <div class="form-radio d-flex items-center ">
                    <div class="radio">
                      <input type="radio" name="name">
                      <div class="radio__mark">
                        <div class="radio__icon"></div>
                      </div>
                    </div>
                    <div class="ml-10">Good 3.5+ </div>
                  </div>

                </div>

                <div class="col-auto">
                  <div class="text-15 text-light-1">78</div>
                </div>
              </div>

            </div>
          </div>

          <div class="mapFilter-filter__item">
            <h5 class="text-18 fw-500 mb-10">Style</h5>
            <div class="sidebar-checkbox">

              <div class="row y-gap-10 items-center justify-between">
                <div class="col-auto">

                  <div class="d-flex items-center">
                    <div class="form-checkbox ">
                      <input type="checkbox" name="name">
                      <div class="form-checkbox__mark">
                        <div class="form-checkbox__icon icon-check"></div>
                      </div>
                    </div>

                    <div class="text-15 ml-10">Budget</div>

                  </div>

                </div>

                <div class="col-auto">
                  <div class="text-15 text-light-1">92</div>
                </div>
              </div>

              <div class="row y-gap-10 items-center justify-between">
                <div class="col-auto">

                  <div class="d-flex items-center">
                    <div class="form-checkbox ">
                      <input type="checkbox" name="name">
                      <div class="form-checkbox__mark">
                        <div class="form-checkbox__icon icon-check"></div>
                      </div>
                    </div>

                    <div class="text-15 ml-10">Mid-range </div>

                  </div>

                </div>

                <div class="col-auto">
                  <div class="text-15 text-light-1">45</div>
                </div>
              </div>

              <div class="row y-gap-10 items-center justify-between">
                <div class="col-auto">

                  <div class="d-flex items-center">
                    <div class="form-checkbox ">
                      <input type="checkbox" name="name">
                      <div class="form-checkbox__mark">
                        <div class="form-checkbox__icon icon-check"></div>
                      </div>
                    </div>

                    <div class="text-15 ml-10">Luxury</div>

                  </div>

                </div>

                <div class="col-auto">
                  <div class="text-15 text-light-1">21</div>
                </div>
              </div>

              <div class="row y-gap-10 items-center justify-between">
                <div class="col-auto">

                  <div class="d-flex items-center">
                    <div class="form-checkbox ">
                      <input type="checkbox" name="name">
                      <div class="form-checkbox__mark">
                        <div class="form-checkbox__icon icon-check"></div>
                      </div>
                    </div>

                    <div class="text-15 ml-10">Family-friendly </div>

                  </div>

                </div>

                <div class="col-auto">
                  <div class="text-15 text-light-1">78</div>
                </div>
              </div>

              <div class="row y-gap-10 items-center justify-between">
                <div class="col-auto">

                  <div class="d-flex items-center">
                    <div class="form-checkbox ">
                      <input type="checkbox" name="name">
                      <div class="form-checkbox__mark">
                        <div class="form-checkbox__icon icon-check"></div>
                      </div>
                    </div>

                    <div class="text-15 ml-10">Business </div>

                  </div>

                </div>

                <div class="col-auto">
                  <div class="text-15 text-light-1">679</div>
                </div>
              </div>

            </div>
          </div>

          <div class="mapFilter-filter__item">
            <h5 class="text-18 fw-500 mb-10">Neighborhood</h5>
            <div class="sidebar-checkbox">

              <div class="row y-gap-10 items-center justify-between">
                <div class="col-auto">

                  <div class="d-flex items-center">
                    <div class="form-checkbox ">
                      <input type="checkbox" name="name">
                      <div class="form-checkbox__mark">
                        <div class="form-checkbox__icon icon-check"></div>
                      </div>
                    </div>

                    <div class="text-15 ml-10">Central London</div>

                  </div>

                </div>

                <div class="col-auto">
                  <div class="text-15 text-light-1">92</div>
                </div>
              </div>

              <div class="row y-gap-10 items-center justify-between">
                <div class="col-auto">

                  <div class="d-flex items-center">
                    <div class="form-checkbox ">
                      <input type="checkbox" name="name">
                      <div class="form-checkbox__mark">
                        <div class="form-checkbox__icon icon-check"></div>
                      </div>
                    </div>

                    <div class="text-15 ml-10">Guests&#39; favourite area </div>

                  </div>

                </div>

                <div class="col-auto">
                  <div class="text-15 text-light-1">45</div>
                </div>
              </div>

              <div class="row y-gap-10 items-center justify-between">
                <div class="col-auto">

                  <div class="d-flex items-center">
                    <div class="form-checkbox ">
                      <input type="checkbox" name="name">
                      <div class="form-checkbox__mark">
                        <div class="form-checkbox__icon icon-check"></div>
                      </div>
                    </div>

                    <div class="text-15 ml-10">Westminster Borough</div>

                  </div>

                </div>

                <div class="col-auto">
                  <div class="text-15 text-light-1">21</div>
                </div>
              </div>

              <div class="row y-gap-10 items-center justify-between">
                <div class="col-auto">

                  <div class="d-flex items-center">
                    <div class="form-checkbox ">
                      <input type="checkbox" name="name">
                      <div class="form-checkbox__mark">
                        <div class="form-checkbox__icon icon-check"></div>
                      </div>
                    </div>

                    <div class="text-15 ml-10">Kensington and Chelsea </div>

                  </div>

                </div>

                <div class="col-auto">
                  <div class="text-15 text-light-1">78</div>
                </div>
              </div>

              <div class="row y-gap-10 items-center justify-between">
                <div class="col-auto">

                  <div class="d-flex items-center">
                    <div class="form-checkbox ">
                      <input type="checkbox" name="name">
                      <div class="form-checkbox__mark">
                        <div class="form-checkbox__icon icon-check"></div>
                      </div>
                    </div>

                    <div class="text-15 ml-10">Oxford Street </div>

                  </div>

                </div>

                <div class="col-auto">
                  <div class="text-15 text-light-1">679</div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>

      <div class="mapFilter-results scroll-bar-1">
        <div class="px-20 py-20 md:px-15 md:py-15">
          <div class="row y-gap-10 justify-between">
            <div class="col-auto">
              <div class="text-14 text-light-1">
                <span class="text-dark-1 text-15 fw-500">3,269 properties</span>
                in Europe
              </div>
            </div>

            <div class="col-auto">
              <button class="button -blue-1 h-40 px-20 md:px-5 text-blue-1 bg-blue-1-05 rounded-100">
                <i class="icon-up-down mr-10"></i>
                Top picks for your search
              </button>
            </div>
          </div>


          <div class="mapFilter-results__item">
            <div class="row x-gap-20 y-gap-20">
              <div class="col-md-auto">
                <div class="ratio ratio-1:1 size-120">
                  <img src="img/hotels/1.png" alt="image" class="img-ratio rounded-4">
                </div>
              </div>

              <div class="col-md">
                <div class="row x-gap-20 y-gap-10 justify-between">
                  <div class="col-lg">
                    <h4 class="text-16 lh-17 fw-500">
                      Great Northern Hotel, a Tribute Portfolio Hotel, London
                      <span class="text-10 text-yellow-2">
                        <i class="icon-star"></i>
                        <i class="icon-star"></i>
                        <i class="icon-star"></i>
                        <i class="icon-star"></i>
                        <i class="icon-star"></i>
                      </span>
                    </h4>
                  </div>

                  <div class="col-auto">
                    <button class="button -blue-1 size-30 flex-center bg-light-2 rounded-full">
                      <i class="icon-heart text-12"></i>
                    </button>
                  </div>
                </div>

                <div class="row y-gap-10 justify-between items-end pt-24 lg:pt-15">
                  <div class="col-auto">
                    <div class="d-flex items-center">
                      <div class="size-38 rounded-4 flex-center bg-blue-1">
                        <span class="text-14 fw-600 text-white">4.8</span>
                      </div>

                      <div class="ml-10">
                        <div class="text-13 lh-14 fw-500">Exceptional</div>
                        <div class="text-12 lh-14 text-light-1">3,014 reviews</div>
                      </div>
                    </div>
                  </div>

                  <div class="col-auto">
                    <div class="d-flex items-center">
                      <div class="text-14 text-light-1 mr-10">8 nights</div>
                      <div class="fw-500">US$72</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="mapFilter-results__item">
            <div class="row x-gap-20 y-gap-20">
              <div class="col-md-auto">
                <div class="ratio ratio-1:1 size-120">
                  <img src="img/hotels/1.png" alt="image" class="img-ratio rounded-4">
                </div>
              </div>

              <div class="col-md">
                <div class="row x-gap-20 y-gap-10 justify-between">
                  <div class="col-lg">
                    <h4 class="text-16 lh-17 fw-500">
                      Great Northern Hotel, a Tribute Portfolio Hotel, London
                      <span class="text-10 text-yellow-2">
                        <i class="icon-star"></i>
                        <i class="icon-star"></i>
                        <i class="icon-star"></i>
                        <i class="icon-star"></i>
                        <i class="icon-star"></i>
                      </span>
                    </h4>
                  </div>

                  <div class="col-auto">
                    <button class="button -blue-1 size-30 flex-center bg-light-2 rounded-full">
                      <i class="icon-heart text-12"></i>
                    </button>
                  </div>
                </div>

                <div class="row y-gap-10 justify-between items-end pt-24 lg:pt-15">
                  <div class="col-auto">
                    <div class="d-flex items-center">
                      <div class="size-38 rounded-4 flex-center bg-blue-1">
                        <span class="text-14 fw-600 text-white">4.8</span>
                      </div>

                      <div class="ml-10">
                        <div class="text-13 lh-14 fw-500">Exceptional</div>
                        <div class="text-12 lh-14 text-light-1">3,014 reviews</div>
                      </div>
                    </div>
                  </div>

                  <div class="col-auto">
                    <div class="d-flex items-center">
                      <div class="text-14 text-light-1 mr-10">8 nights</div>
                      <div class="fw-500">US$72</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="mapFilter-results__item">
            <div class="row x-gap-20 y-gap-20">
              <div class="col-md-auto">
                <div class="ratio ratio-1:1 size-120">
                  <img src="img/hotels/1.png" alt="image" class="img-ratio rounded-4">
                </div>
              </div>

              <div class="col-md">
                <div class="row x-gap-20 y-gap-10 justify-between">
                  <div class="col-lg">
                    <h4 class="text-16 lh-17 fw-500">
                      Great Northern Hotel, a Tribute Portfolio Hotel, London
                      <span class="text-10 text-yellow-2">
                        <i class="icon-star"></i>
                        <i class="icon-star"></i>
                        <i class="icon-star"></i>
                        <i class="icon-star"></i>
                        <i class="icon-star"></i>
                      </span>
                    </h4>
                  </div>

                  <div class="col-auto">
                    <button class="button -blue-1 size-30 flex-center bg-light-2 rounded-full">
                      <i class="icon-heart text-12"></i>
                    </button>
                  </div>
                </div>

                <div class="row y-gap-10 justify-between items-end pt-24 lg:pt-15">
                  <div class="col-auto">
                    <div class="d-flex items-center">
                      <div class="size-38 rounded-4 flex-center bg-blue-1">
                        <span class="text-14 fw-600 text-white">4.8</span>
                      </div>

                      <div class="ml-10">
                        <div class="text-13 lh-14 fw-500">Exceptional</div>
                        <div class="text-12 lh-14 text-light-1">3,014 reviews</div>
                      </div>
                    </div>
                  </div>

                  <div class="col-auto">
                    <div class="d-flex items-center">
                      <div class="text-14 text-light-1 mr-10">8 nights</div>
                      <div class="fw-500">US$72</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="mapFilter-results__item">
            <div class="row x-gap-20 y-gap-20">
              <div class="col-md-auto">
                <div class="ratio ratio-1:1 size-120">
                  <img src="img/hotels/1.png" alt="image" class="img-ratio rounded-4">
                </div>
              </div>

              <div class="col-md">
                <div class="row x-gap-20 y-gap-10 justify-between">
                  <div class="col-lg">
                    <h4 class="text-16 lh-17 fw-500">
                      Great Northern Hotel, a Tribute Portfolio Hotel, London
                      <span class="text-10 text-yellow-2">
                        <i class="icon-star"></i>
                        <i class="icon-star"></i>
                        <i class="icon-star"></i>
                        <i class="icon-star"></i>
                        <i class="icon-star"></i>
                      </span>
                    </h4>
                  </div>

                  <div class="col-auto">
                    <button class="button -blue-1 size-30 flex-center bg-light-2 rounded-full">
                      <i class="icon-heart text-12"></i>
                    </button>
                  </div>
                </div>

                <div class="row y-gap-10 justify-between items-end pt-24 lg:pt-15">
                  <div class="col-auto">
                    <div class="d-flex items-center">
                      <div class="size-38 rounded-4 flex-center bg-blue-1">
                        <span class="text-14 fw-600 text-white">4.8</span>
                      </div>

                      <div class="ml-10">
                        <div class="text-13 lh-14 fw-500">Exceptional</div>
                        <div class="text-12 lh-14 text-light-1">3,014 reviews</div>
                      </div>
                    </div>
                  </div>

                  <div class="col-auto">
                    <div class="d-flex items-center">
                      <div class="text-14 text-light-1 mr-10">8 nights</div>
                      <div class="fw-500">US$72</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="mapFilter-results__item">
            <div class="row x-gap-20 y-gap-20">
              <div class="col-md-auto">
                <div class="ratio ratio-1:1 size-120">
                  <img src="img/hotels/1.png" alt="image" class="img-ratio rounded-4">
                </div>
              </div>

              <div class="col-md">
                <div class="row x-gap-20 y-gap-10 justify-between">
                  <div class="col-lg">
                    <h4 class="text-16 lh-17 fw-500">
                      Great Northern Hotel, a Tribute Portfolio Hotel, London
                      <span class="text-10 text-yellow-2">
                        <i class="icon-star"></i>
                        <i class="icon-star"></i>
                        <i class="icon-star"></i>
                        <i class="icon-star"></i>
                        <i class="icon-star"></i>
                      </span>
                    </h4>
                  </div>

                  <div class="col-auto">
                    <button class="button -blue-1 size-30 flex-center bg-light-2 rounded-full">
                      <i class="icon-heart text-12"></i>
                    </button>
                  </div>
                </div>

                <div class="row y-gap-10 justify-between items-end pt-24 lg:pt-15">
                  <div class="col-auto">
                    <div class="d-flex items-center">
                      <div class="size-38 rounded-4 flex-center bg-blue-1">
                        <span class="text-14 fw-600 text-white">4.8</span>
                      </div>

                      <div class="ml-10">
                        <div class="text-13 lh-14 fw-500">Exceptional</div>
                        <div class="text-12 lh-14 text-light-1">3,014 reviews</div>
                      </div>
                    </div>
                  </div>

                  <div class="col-auto">
                    <div class="d-flex items-center">
                      <div class="text-14 text-light-1 mr-10">8 nights</div>
                      <div class="fw-500">US$72</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="mapFilter-results__item">
            <div class="row x-gap-20 y-gap-20">
              <div class="col-md-auto">
                <div class="ratio ratio-1:1 size-120">
                  <img src="img/hotels/1.png" alt="image" class="img-ratio rounded-4">
                </div>
              </div>

              <div class="col-md">
                <div class="row x-gap-20 y-gap-10 justify-between">
                  <div class="col-lg">
                    <h4 class="text-16 lh-17 fw-500">
                      Great Northern Hotel, a Tribute Portfolio Hotel, London
                      <span class="text-10 text-yellow-2">
                        <i class="icon-star"></i>
                        <i class="icon-star"></i>
                        <i class="icon-star"></i>
                        <i class="icon-star"></i>
                        <i class="icon-star"></i>
                      </span>
                    </h4>
                  </div>

                  <div class="col-auto">
                    <button class="button -blue-1 size-30 flex-center bg-light-2 rounded-full">
                      <i class="icon-heart text-12"></i>
                    </button>
                  </div>
                </div>

                <div class="row y-gap-10 justify-between items-end pt-24 lg:pt-15">
                  <div class="col-auto">
                    <div class="d-flex items-center">
                      <div class="size-38 rounded-4 flex-center bg-blue-1">
                        <span class="text-14 fw-600 text-white">4.8</span>
                      </div>

                      <div class="ml-10">
                        <div class="text-13 lh-14 fw-500">Exceptional</div>
                        <div class="text-12 lh-14 text-light-1">3,014 reviews</div>
                      </div>
                    </div>
                  </div>

                  <div class="col-auto">
                    <div class="d-flex items-center">
                      <div class="text-14 text-light-1 mr-10">8 nights</div>
                      <div class="fw-500">US$72</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="mapFilter-results__item">
            <div class="row x-gap-20 y-gap-20">
              <div class="col-md-auto">
                <div class="ratio ratio-1:1 size-120">
                  <img src="img/hotels/1.png" alt="image" class="img-ratio rounded-4">
                </div>
              </div>

              <div class="col-md">
                <div class="row x-gap-20 y-gap-10 justify-between">
                  <div class="col-lg">
                    <h4 class="text-16 lh-17 fw-500">
                      Great Northern Hotel, a Tribute Portfolio Hotel, London
                      <span class="text-10 text-yellow-2">
                        <i class="icon-star"></i>
                        <i class="icon-star"></i>
                        <i class="icon-star"></i>
                        <i class="icon-star"></i>
                        <i class="icon-star"></i>
                      </span>
                    </h4>
                  </div>

                  <div class="col-auto">
                    <button class="button -blue-1 size-30 flex-center bg-light-2 rounded-full">
                      <i class="icon-heart text-12"></i>
                    </button>
                  </div>
                </div>

                <div class="row y-gap-10 justify-between items-end pt-24 lg:pt-15">
                  <div class="col-auto">
                    <div class="d-flex items-center">
                      <div class="size-38 rounded-4 flex-center bg-blue-1">
                        <span class="text-14 fw-600 text-white">4.8</span>
                      </div>

                      <div class="ml-10">
                        <div class="text-13 lh-14 fw-500">Exceptional</div>
                        <div class="text-12 lh-14 text-light-1">3,014 reviews</div>
                      </div>
                    </div>
                  </div>

                  <div class="col-auto">
                    <div class="d-flex items-center">
                      <div class="text-14 text-light-1 mr-10">8 nights</div>
                      <div class="fw-500">US$72</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>

      <div class="mapFilter-map">
        <div class="map js-map"></div>
      </div>
    </div>
  </div>

  <!-- JavaScript -->
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAAz77U5XQuEME6TpftaMdX0bBelQxXRlM"></script>
  <script src="https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js"></script>

  <script src="js/vendors.js"></script>
  <script src="js/main.js"></script>
</body>

</html>