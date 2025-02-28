<?php

$dsn = 'mysql:dbname=onemix;host=127.0.0.1';
$user = 'root';
$password = '';
$pdo = new PDO($dsn, $user, $password);

if (count($_POST) > 0) {
  $error = false;
  $profile = $_POST['profile'];
  $firstName = $_POST['first_name'];
  $lastName = $_POST['last_name'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $confirmPassword = $_POST['confirm_password'];

  if (empty($profile) || empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($confirmPassword)) {
    $error = true;
    echo "Tous les champs ne sont pas remplis!";
  }

  if ($password !== $confirmPassword) {
    $error = true;
    echo "Les mots de passes diffèrent!";
  }

  if (!$error) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (profile, first_name, last_name, email, password) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$profile, $firstName, $lastName, $email, $hashedPassword]);
    echo "Inscription réussie!";
    header('Location: login.php');
  }
  else {
    echo "Inscription échouée!";
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
  
                      <li class="menu-item-has-children">
                        <a data-barba href="">
                          <span class="mr-10">Home</span>
                          <i class="icon icon-chevron-sm-down"></i>
                        </a>
  
  
                        <ul class="subnav">
                          <li class="subnav__backBtn js-nav-list-back">
                            <a href="index.html"><i class="icon icon-chevron-sm-down"></i> Home</a>
                          </li>
  
                          <li><a href="index.html">Home</a></li>
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
          <!-- Titre et lien de connexion -->
          <div class="row y-gap-20 mb-3">
            <div class="col-12">
              <h1 class="text-22 fw-500">Sign in or create an account</h1>
              <p class="mt-10">Already have an account? <a href="login.php" class="text-blue-1">Log in</a></p>
            </div>
          </div>

          <!-- Formulaire d'inscription -->
          <form action="signup.php" method="POST">
            <!-- Champ pour le type de profil -->
            <div class="col-12">
              <div class="form-input">

                <select name="profile" required>
                  <option value="artist">Artist</option>
                  <option value="studio">Studio</option>
                </select>
              </div>
            </div>

            <!-- Champ pour le prénom -->
            <div class="col-12 mt-3">
              <div class="form-input">
                <input type="text" name="first_name" required>
                <label class="lh-1 text-14 text-light-1">First Name</label>
              </div>
            </div>

            <!-- Champ pour le nom de famille -->
            <div class="col-12 mt-3">
              <div class="form-input">
                <input type="text" name="last_name" required>
                <label class="lh-1 text-14 text-light-1">Last Name</label>
              </div>
            </div>

            <!-- Champ pour l'e-mail -->
            <div class="col-12 mt-3">
              <div class="form-input">
                <input type="email" name="email" required>
                <label class="lh-1 text-14 text-light-1">Email</label>
              </div>
            </div>

            <!-- Champ pour le mot de passe -->
            <div class="col-12 mt-3">
              <div class="form-input">
                <input type="password" name="password" required>
                <label class="lh-1 text-14 text-light-1">Password</label>
              </div>
            </div>

            <!-- Champ pour confirmer le mot de passe -->
            <div class="col-12 mt-3">
              <div class="form-input">
                <input type="password" name="confirm_password" required>
                <label class="lh-1 text-14 text-light-1">Confirm Password</label>
              </div>
            </div>

            <!-- Case à cocher pour les promotions -->
            <div class="d-flex align-items-center">
              <div class="form-checkbox mt-5">
                  <input type="checkbox" name="gcu" id="gcu" required>
                  <div class="form-checkbox__mark">
                      <div class="form-checkbox__icon icon-check"></div>
                  </div>
              </div>

              <div class="text-15 lh-15 text-light-1 ml-10 mt-3 mb-3">
                  J'accepte les <a href="#" class="text-blue-1" target="_blank">Conditions d'utilisation</a> et la <a href="#" class="text-blue-1" target="_blank">Politique de confidentialité</a>.
              </div>
          </div>

            <!-- Bouton de soumission -->
            <div class="col-12 mt-3 d-flex justify-content-center">
                <button type="submit" class="button py-20 -dark-1 bg-blue-1 text-white mx-auto d-block" style="width: 530px;">
                    S'inscrire<span class="icon-arrow-top-right ml-15"></span>
                </button>
            </div>
          </form>
            <!-- Mentions légales -->
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


  <!-- JavaScript -->
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAAz77U5XQuEME6TpftaMdX0bBelQxXRlM"></script>
  <script src="https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js"></script>

  <script src="js/vendors.js"></script>
  <script src="js/main.js"></script>
</body>

</html>