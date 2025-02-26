<?php
session_start(); // Démarrer la session

if (isset($_SESSION['user'])) {
    // L'utilisateur est connecté
    $user = $_SESSION['user'];

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
  <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />

  <title>MixOne</title>
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

    <div class="preloader__title">MixOne</div>
  </div>
  <header data-add-bg="bg-dark-1" class="header bg-green js-header" data-x="header" data-x-toggle="is-menu-opened">
    <div data-anim="fade" class="header__container px-30 sm:px-20">
      <div class="row justify-between items-center">

        <div class="col-auto">
          <div class="d-flex items-center">
            <a href="index.html" class="header-logo mr-20" data-x="header-logo" data-x-toggle="is-logo-dark">
              <img src="img/general/logo-light.svg" alt="logo icon">
              <img src="img/general/logo-dark.svg" alt="logo icon">
            </a>


            <div class="header-menu " data-x="mobile-menu" data-x-toggle="is-menu-active">
              <div class="mobile-overlay"></div>

              <div class="header-menu__content">
                <div class="mobile-bg js-mobile-bg"></div>

                <div class="menu js-navList">
                  <ul class="menu__nav text-white -is-active">

                  <li class="subnav__backBtn js-nav-list-back">

                        <li><a href="index.html">Home</a></li>


                        <li class="subnav__backBtn js-nav-list-back">
                        <li><a href="db-dashboard.php">Dashboard</a></li>
                  </ul>
                </div>

                <div class="mobile-footer px-20 py-20 border-top-light js-mobile-footer">
                </div>
              </div>
            </div>

          </div>
        </div>


        <div class="col-auto">
          <div class="d-flex items-center">

            <div class="row x-gap-20 items-center xxl:d-none">
              <div class="col-auto">
                <button class="d-flex items-center text-14 text-white" data-x-click="currency">
                  <span class="js-currencyMenu-mainTitle">USD</span>
                  <i class="icon-chevron-sm-down text-7 ml-10"></i>
                </button>
              </div>

              <div class="col-auto">
                <div class="w-1 h-20 bg-white-20"></div>
              </div>

              <div class="col-auto">
                <button class="d-flex items-center text-14 text-white" data-x-click="lang">
                  <img src="img/general/lang.png" alt="image" class="rounded-full mr-10">
                  <span class="js-language-mainTitle">United Kingdom</span>
                  <i class="icon-chevron-sm-down text-7 ml-15"></i>
                </button>
              </div>
            </div>


            <div class="d-flex items-center ml-20 is-menu-opened-hide md:d-none">
              <a href="login.php" class="button px-30 fw-400 text-14 -white bg-white h-50 text-dark-1">Become A Confirmed Studio</a>
              <a href="signup.php" class="button px-30 fw-400 text-14 border-white -outline-white h-50 text-white ml-20">Sign In / Register</a>
            </div>

            <div class="d-none xl:d-flex x-gap-20 items-center pl-30 text-white" data-x="header-mobile-icons" data-x-toggle="text-white">
              <div><a href="login.php" class="d-flex items-center icon-user text-inherit text-22"></a></div>
              <div><button class="d-flex items-center icon-menu text-inherit text-20" data-x-click="html, header, header-logo, header-mobile-icons, mobile-menu"></button></div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </header>


  <main>
    <section data-anim-wrap class="masthead -type-1 z-5">
        <div data-anim-child="fade" class="masthead__bg">
            <img src="" data-src="img/masthead/1/11.jpg" alt="image" class="js-lazy">
        </div>

        <div class="container">
            <div class="row justify-center">
                <div class="col-auto">
                    <div class="text-center">
                        <h1 data-anim-child="slide-up delay-4" class="text-60 lg:text-40 md:text-30 text-white">Découvrez votre prochain studio</h1>
                        <p data-anim-child="slide-up delay-5" class="text-white mt-6 md:mt-10">Trouvez d'incroyables studios aux meilleurs prix</p>
                    </div>

                    <div data-anim-child="slide-up delay-6" class="tabs -underline mt-60 js-tabs">
                        <div class="tabs__content mt-30 md:mt-20 js-tabs-content">
                            <div class="tabs__pane -tab-item-1 is-tab-el-active">
                                <div class="mainSearch -w-900 bg-white px-10 py-10 lg:px-20 lg:pt-5 lg:pb-20 rounded-100">
                                <form id="searchForm" action="search.php" method="GET">
                                    <div class="button-grid items-center">
                                        <!-- Champ pour la ville -->
                                        <div class="searchMenu-loc px-30 lg:py-20 lg:px-0">
                                            <label for="city" class="text-15 fw-500 ls-2 lh-16">City</label>
                                            <input type="text" id="city" name="city" placeholder="City" class="js-search js-dd-focus" />
                                        </div>

                                        <!-- Champ pour les heures minimum -->
                                        <div class="searchMenu-guests px-30 lg:py-20 lg:px-0 position-relative">
                                            <label for="min_hours" class="text-15 fw-500 ls-2 lh-16">Hours</label>
                                            <input type="text" id="min_hours" name="min_hours" placeholder="Hours" class="text-15 text-light-1 ls-2 lh-16" onclick="toggleHoursMenu(event)" readonly />
                                            <div id="hoursMenu" class="hours-menu hidden">
                                                <button type="button" class="button -outline-blue-1 text-blue-1 size-38 rounded-4" onclick="changeHours(-1)">
                                                    <i class="icon-minus text-12"></i>
                                                </button>
                                                <div class="flex-center size-20 ml-15 mr-15">
                                                    <div id="hoursValue" class="text-15">2</div>
                                                </div>
                                                <button type="button" class="button -outline-blue-1 text-blue-1 size-38 rounded-4" onclick="changeHours(1)">
                                                    <i class="icon-plus text-12"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Bouton de recherche -->
                                        <div class="button-item">
                                            <button type="submit" class="mainSearch__submit button -dark-1 h-60 px-35 col-12 rounded-100 bg-blue-1 text-white">
                                                <i class="icon-search text-20 mr-10"></i>
                                                Search
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .hidden {
            display: none;
        }
        .hours-menu {
            display: flex;
            gap: 10px;
            align-items: center;
            position: absolute;
            background: white;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            z-index: 1000;
            top: 100%;
            left: 0;
        }
    </style>

    <script>
        function toggleHoursMenu(event) {
            event.stopPropagation();
            const menu = document.getElementById('hoursMenu');
            menu.classList.toggle('hidden');
        }

        function changeHours(amount) {
            const hoursInput = document.getElementById('min_hours');
            const hoursValue = document.getElementById('hoursValue');
            let currentValue = parseInt(hoursValue.textContent);
            if (!isNaN(currentValue)) {
                currentValue += amount;
                if (currentValue < 1) {
                    currentValue = 1;
                }
                hoursValue.textContent = currentValue;
                hoursInput.value = currentValue;
            }
        }

        document.addEventListener('click', function(event) {
            const hoursInput = document.getElementById('min_hours');
            const hoursMenu = document.getElementById('hoursMenu');

            if (!hoursInput.contains(event.target) && !hoursMenu.contains(event.target)) {
                hoursMenu.classList.add('hidden');
            }
        });

        document.getElementById('hoursMenu').addEventListener('click', function(event) {
            event.stopPropagation();
        });
    </script>

<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$dsn = 'mysql:dbname=onemix;host=127.0.0.1:8889';
$user = 'root';
$password = 'root';

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

$city = isset($_GET['city']) ? trim($_GET['city']) : '';
$min_hours = isset($_GET['min_hours']) ? intval($_GET['min_hours']) : 0;

$sql = "SELECT * FROM studios WHERE city LIKE :city AND min_hours >= :min_hours";
$stmt = $pdo->prepare($sql);
$stmt->execute(['city' => "%$city%", 'min_hours' => $min_hours]);
$studios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Afficher les résultats de la recherche -->
<section class="layout-pt-md layout-pb-md">
    <div data-anim="slide-up delay-1" class="container">
        <div class="row y-gap-10 justify-between items-end">
            <div class="col-auto">
                <div class="sectionTitle -md">
                    <h2 class="sectionTitle__title">Résultats de la recherche</h2>
                    <p class="sectionTitle__text mt-5 sm:mt-0">Studios correspondant à vos critères</p>
                </div>
            </div>
        </div>

        <div class="relative overflow-hidden pt-40 sm:pt-20">
            <div class="row y-gap-30">
                <?php if (empty($studios)) : ?>
                    <div class="col-12">
                        <p class="text-center">Aucun studio trouvé.</p>
                    </div>
                <?php else : ?>
                    <?php foreach ($studios as $studio) : ?>
                        <div class="col-xl-3 col-lg-4 col-sm-6">
                            <a href="#" class="hotelsCard -type-1">
                                <div class="hotelsCard__image">
                                    <div class="cardImage ratio ratio-1:1">
                                        <div class="cardImage__content">
                                            <img class="col-12" src="img/backgrounds/11.jpg" alt="<?= htmlspecialchars($studio['name']) ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="hotelsCard__content mt-10">
                                    <h4 class="hotelsCard__title text-dark-1 text-18 lh-16 fw-500">
                                        <?= htmlspecialchars($studio['name']) ?>
                                    </h4>
                                    <p class="text-light-1 lh-14 text-14 mt-5"><?= htmlspecialchars($studio['city']) ?></p>
                                    <div class="d-flex items-center mt-20">
                                        <div class="flex-center bg-blue-1 rounded-4 size-30 text-12 fw-600 text-white">4.8</div>
                                        <div class="text-14 text-dark-1 fw-500 ml-10">Exceptional</div>
                                        <div class="text-14 text-light-1 ml-10">3,014 reviews</div>
                                    </div>
                                    <div class="mt-5">
                                        <div class="fw-500">
                                            A Partir de <span class="text-blue-1"><?= htmlspecialchars($studio['hourly_rate']) ?>€</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

    <section class="layout-pt-md layout-pb-lg">
      <div data-anim-wrap class="container">
        <div class="row y-gap-20 justify-between">

          <div data-anim-child="slide-up delay-1" class="col-lg-3 col-sm-6">

            <div class="featureIcon -type-1 ">
              <div class="d-flex justify-center">
                <img src="#" data-src="img/featureIcons/1/1.svg" alt="image" class="js-lazy">
              </div>

              <div class="text-center mt-30">
                <h4 class="text-18 fw-500">Best Price Guarantee</h4>
                <p class="text-15 mt-10">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
              </div>
            </div>

          </div>

          <div data-anim-child="slide-up delay-2" class="col-lg-3 col-sm-6">

            <div class="featureIcon -type-1 ">
              <div class="d-flex justify-center">
                <img src="#" data-src="img/featureIcons/1/2.svg" alt="image" class="js-lazy">
              </div>

              <div class="text-center mt-30">
                <h4 class="text-18 fw-500">Easy & Quick Booking</h4>
                <p class="text-15 mt-10">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
              </div>
            </div>

          </div>

          <div data-anim-child="slide-up delay-3" class="col-lg-3 col-sm-6">

            <div class="featureIcon -type-1 ">
              <div class="d-flex justify-center">
                <img src="#" data-src="img/featureIcons/1/3.svg" alt="image" class="js-lazy">
              </div>

              <div class="text-center mt-30">
                <h4 class="text-18 fw-500">Customer Care 24/7</h4>
                <p class="text-15 mt-10">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
              </div>
            </div>

          </div>

        </div>
      </div>
    </section>

    <section class="layout-pt-lg layout-pb-lg bg-blue-2">
      <div data-anim-wrap class="container">
        <div class="row y-gap-40 justify-between">
          <div data-anim-child="slide-up delay-1" class="col-xl-5 col-lg-6">
            <h2 class="text-30">What our customers are<br> saying us?</h2>
            <p class="mt-20">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas varius tortor nibh, sit amet tempor nibh finibus et. Aenean eu enim justo.</p>

            <div class="row y-gap-30 pt-60 lg:pt-40">
              <div class="col-sm-5 col-6">
                <div class="text-30 lh-15 fw-600">13m+</div>
                <div class="text-light-1 lh-15">Happy People</div>
              </div>

              <div class="col-sm-5 col-6">
                <div class="text-30 lh-15 fw-600">4.88</div>
                <div class="text-light-1 lh-15">Overall rating</div>

                <div class="d-flex x-gap-5 items-center pt-10">

                  <div class="icon-star text-blue-1 text-10"></div>

                  <div class="icon-star text-blue-1 text-10"></div>

                  <div class="icon-star text-blue-1 text-10"></div>

                  <div class="icon-star text-blue-1 text-10"></div>

                  <div class="icon-star text-blue-1 text-10"></div>

                </div>
              </div>
            </div>
          </div>

          <div data-anim-child="slide-up delay-2" class="col-lg-6">
            <div class="overflow-hidden js-testimonials-slider-3" data-scrollbar>
              <div class="swiper-wrapper">

                <div class="swiper-slide">
                  <div class="row items-center x-gap-30 y-gap-20">
                    <div class="col-auto">
                      <img src="#" data-src="img/avatars/1.png" alt="image" class="js-lazy">
                    </div>

                    <div class="col-auto">
                      <h5 class="text-16 fw-500">Annette Black</h5>
                      <div class="text-15 text-light-1 lh-15">UX / UI Designer</div>
                    </div>
                  </div>

                  <p class="text-18 fw-500 text-dark-1 mt-30 sm:mt-20">The place is in a great location in Gumbet. The area is safe and beautiful. The apartment was comfortable and the host was kind and responsive to our requests.</p>
                </div>

                <div class="swiper-slide">
                  <div class="row items-center x-gap-30 y-gap-20">
                    <div class="col-auto">
                      <img src="#" data-src="img/avatars/1.png" alt="image" class="js-lazy">
                    </div>

                    <div class="col-auto">
                      <h5 class="text-16 fw-500">Annette Black</h5>
                      <div class="text-15 text-light-1 lh-15">UX / UI Designer</div>
                    </div>
                  </div>

                  <p class="text-18 fw-500 text-dark-1 mt-30 sm:mt-20">The place is in a great location in Gumbet. The area is safe and beautiful. The apartment was comfortable and the host was kind and responsive to our requests.</p>
                </div>

                <div class="swiper-slide">
                  <div class="row items-center x-gap-30 y-gap-20">
                    <div class="col-auto">
                      <img src="#" data-src="img/avatars/1.png" alt="image" class="js-lazy">
                    </div>

                    <div class="col-auto">
                      <h5 class="text-16 fw-500">Annette Black</h5>
                      <div class="text-15 text-light-1 lh-15">UX / UI Designer</div>
                    </div>
                  </div>

                  <p class="text-18 fw-500 text-dark-1 mt-30 sm:mt-20">The place is in a great location in Gumbet. The area is safe and beautiful. The apartment was comfortable and the host was kind and responsive to our requests.</p>
                </div>

              </div>

              <div class="d-flex items-center mt-60 sm:mt-20 js-testimonials-slider-pag">
                <div class="text-dark-1 fw-500 js-current">01</div>
                <div class="slider-scrollbar bg-border ml-20 mr-20 w-max-300 js-scrollbar"></div>
                <div class="text-dark-1 fw-500 js-all">05</div>
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
          </div>
        </div>

        <div class="py-20 border-top-light">
          <div class="row justify-between items-center y-gap-10">
            <div class="col-auto">
              <div class="row x-gap-30 y-gap-10">
                <div class="col-auto">
                  <div class="d-flex items-center">
                    © 2025 MixOne LLC All rights reserved.
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


  <!-- JavaScript -->
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAAz77U5XQuEME6TpftaMdX0bBelQxXRlM"></script>
  <script src="https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js"></script>

  <script src="js/vendors.js"></script>
  <script src="js/main.js"></script>
</body>

</html>