<!doctype html>
<html lang="pt_BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Desiree Swing Club</title>
    <!-- CSS FILES -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;200;400;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    
</head>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-B0FE0C9J1S"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-B0FE0C9J1S');
</script>

<body>
    <main>
        {{-- <header class="site-header">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-12 d-flex flex-wrap">
                        <p class="d-flex me-4 mb-0">
                           
                        </p>
                    </div>
                </div>
            </div>
        </header> --}}

        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.html">
                    <img src="{{ asset('images/logo.png') }}" alt="Desiree Swing Club" class="img-fluid">
                </a>
              <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>
              <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link click-scroll" href="#section_1">Principal</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Sobre
                        </a>
                        <ul class="dropdown-menu">
                          <li><a class="dropdown-item" href="#">FAQ</a></li>
                          <li><a class="dropdown-item" href="#">Estrutura</a></li>
                          <li><hr class="dropdown-divider"></li>
                          <li><a class="dropdown-item" href="#">Reserva</a></li>
                        </ul>
                      </li>
                    

                    <li class="nav-item">
                        <a class="nav-link click-scroll" href="#section_3">Eventos</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link click-scroll" href="#section_4">Programação</a>
                    </li>

                     <li class="nav-item">
                        <a class="nav-link click-scroll" href="#section_5">Assinatura</a>
                    </li> 

                    <li class="nav-item">
                        <a class="nav-link click-scroll" href="#section_6">Contato</a>
                    </li>
                    @if (Route::has('login'))                
                        @auth
                            <li class="nav-item">
                            <a href="{{ url('/dashboard') }}" class="btn custom-btn d-lg-block d-none mt-2">Area VIP</a>    
                            </li>                    
                        @else
                            <li class="nav-item">
                                <a href="{{ route('login') }}" class="nav-link"><i class="bi bi-person"></i>Entrar</a>
                            </li>                           

                            @if (Route::has('register'))
                            <li class="nav-item">
                                <a href="{{ route('register') }}" class="nav-link"><i class="bi bi-person-plus"></i>Registrar</a>
                            </li>                                
                            @endif
                        @endauth                
                    @endif
                  
                </ul>
                
               
              </div>
            </div>
          </nav>


        <section class="hero-section" id="section_1">
            <div class="section-overlay"></div>
            <div class="container d-flex justify-content-center align-items-center">
                <div class="row">
                    <div class="col-12 mt-auto mb-5 text-center">
                        <small>Desiree Swing Club apresenta</small>
                        <h1 class="text-white mb-5">Aniversário do Mau</h1>
                        <a class="btn custom-btn smoothscroll" href="#section_2">Nome na lista</a>
                    </div>
                    <div class="col-lg-12 col-12 mt-auto d-flex flex-column flex-lg-row text-center">
                        <div class="date-wrap">
                            <h5 class="text-white">
                                <i class="custom-icon bi-clock me-2"></i>
                                21 - 22<sup></sup>, Março 2025
                            </h5>
                        </div>
                        <div class="location-wrap mx-auto py-3 py-lg-0">
                            <h5 class="text-white">
                                <i class="custom-icon bi-geo-alt me-2"></i>
                                Rua Brasilio Cuman, 2100. Curitiba, Paraná. Brasil
                            </h5>
                        </div>
                        <div class="social-share">
                            <ul class="social-icon d-flex align-items-center justify-content-center">
                                <span class="text-white me-3">Compartilhar:</span>

                                <li class="social-icon-item">
                                    <a href="#" class="social-icon-link">
                                        <span class="bi-facebook"></span>
                                    </a>
                                </li>
                                <li class="social-icon-item">
                                    <a href="https://x.com/CLUBDESIREE" class="social-icon-link" target="_blank">
                                        <span class="bi-twitter"></span>
                                    </a>
                                </li>
                                <li class="social-icon-item">
                                    <a href="#" class="social-icon-link">
                                        <span class="bi-instagram"></span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="video-wrap">
                <video autoplay="" loop="" muted="" class="custom-video" poster="">
                    <source src="video/pexels-2022395.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
        </section>
        <section class="about-section section-padding" id="section_2">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-12 mb-4 mb-lg-0 d-flex align-items-center" data-aos="fade-right">
                        <div class="services-info">
                            <h2 class="text-white mb-4">Sobre a Desiree</h2>
                            <p class="text-white">Somos o Desiree Swing Club...</p>
        
                            <h6 class="text-white mt-4">Venham conhecer tudo o que temos a oferecer</h6>
                            <p class="text-white">Com 1200m² divididos em vários ambientes...</p>
        
                            <h6 class="text-white mt-4">Nossa Proposta</h6>
                            <p class="text-white">ser o espaço ideal para quem busca conhecer...</p>
                        </div>
                    </div>
        
                    <div class="col-lg-6 col-12" data-aos="fade-left">
                        <div class="about-text-wrap">
                            <img src="{{ asset('images/pexels-alexander-suhorucov-6457579.jpg') }}" class="about-image img-fluid">
                            <div class="about-text-info d-flex" data-aos="zoom-in" data-aos-delay="300">
                                <div class="d-flex">
                                    <i class="about-text-icon bi bi-arrow-through-heart"></i>
                                </div>
                                <div class="ms-4">
                                    <h3>Desiree Swing Club</h3>
                                    <p class="mb-0">Caso ainda não tenhamos nos conhecido, muito prazer!</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        

        <section class="artists-section section-padding" id="section_3">
            <div class="container">
                <div class="row justify-content-center">
        
                    <div class="col-12 text-center" data-aos="fade-up">
                        <h2 class="mb-4">Atrações</h2>
                    </div>
        
                    <div class="col-lg-5 col-12" data-aos="zoom-in" data-aos-delay="100">
                        <div class="artists-thumb">
                            <div class="artists-image-wrap">
                                <img src="images/artists/joecalih-UmTZqmMvQcw-unsplash.jpg" class="artists-image img-fluid">
                            </div>
                            <div class="artists-hover">
                                <p><strong>Name:</strong> Madona</p>
                                <p><strong>Birthdate:</strong> August 16, 1958</p>
                                <p><strong>Music:</strong> Pop, R&amp;B</p>
                                <hr>
                                <p class="mb-0"><strong>Youtube Channel:</strong> <a href="#">Madona Official</a></p>
                            </div>
                        </div>
                    </div>
        
                    <div class="col-lg-5 col-12" data-aos="zoom-in" data-aos-delay="200">
                        <div class="artists-thumb mb-4">
                            <div class="artists-image-wrap">
                                <img src="images/artists/abstral-official-bdlMO9z5yco-unsplash.jpg" class="artists-image img-fluid">
                            </div>
                            <div class="artists-hover">
                                <p><strong>Name:</strong> Rihana</p>
                                <p><strong>Birthdate:</strong> Feb 20, 1988</p>
                                <p><strong>Music:</strong> Country</p>
                                <hr>
                                <p class="mb-0"><strong>Youtube Channel:</strong> <a href="#">Rihana Official</a></p>
                            </div>
                        </div>
        
                        <div class="artists-thumb" data-aos="zoom-in" data-aos-delay="300">
                            <img src="images/artists/soundtrap-rAT6FJ6wltE-unsplash.jpg" class="artists-image img-fluid">
                            <div class="artists-hover">
                                <p><strong>Name:</strong> Bruno Bros</p>
                                <p><strong>Birthdate:</strong> October 8, 1985</p>
                                <p><strong>Music:</strong> Pop</p>
                                <hr>
                                <p class="mb-0"><strong>Youtube Channel:</strong> <a href="#">Bruno Official</a></p>
                            </div>
                        </div>
                    </div>
        
                </div>
            </div>
        </section>
        


        <section class="schedule-section section-padding" id="section_4">
            <div class="container">
                <div class="row">

                    <div class="col-12 text-center">
                        <h2 class="text-white mb-4">Programação</h1>

                            <div class="table-responsive">
                                <table class="schedule-table table table-dark">
                                    <thead>
                                        <tr>
                                            <th scope="col" style="width:10%">Data</th>

                                            <th scope="col">Quarta</th>

                                            <th scope="col">Sexta</th>

                                            <th scope="col">Sábado</th>

                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr>
                                            <th scope="row">2 a 7 março</th>

                                            <td class="table-background-image-wrap pop-background-image">
                                                <h3>&nbsp;</h3>

                                                <p class="mb-2">&nbsp;</p>

                                                <p>&nbsp;</p>

                                                <div class="section-overlay"></div>
                                            </td>

                                            <td style="background-color: #F3DCD4"></td>

                                            <td class="table-background-image-wrap rock-background-image">
                                                <h3>&nbsp;</h3>

                                                <p class="mb-2">&nbsp;</p>

                                                <p>&nbsp;</p>

                                                <div class="section-overlay"></div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th scope="row"> 10 a 14 março</th>

                                            <td style="background-color: #ECC9C7"></td>

                                            <td>
                                                <h3>&nbsp;</h3>

                                                <p class="mb-2">&nbsp;</p>

                                                <p>&nbsp;</p>
                                            </td>

                                            <td style="background-color: #D9E3DA"></td>
                                        </tr>

                                        <tr>
                                            <th scope="row">17 a 21 de Março</th>

                                            <td class="table-background-image-wrap country-background-image">
                                                <h3>&nbsp;</h3>

                                                <p class="mb-2">&nbsp;</p>

                                                <p>&nbsp;</p>

                                                <div class="section-overlay"></div>
                                            </td>

                                            <td style="background-color: #D1CFC0"></td>

                                            <td>
                                                <h3>&nbsp;</h3>

                                                <p class="mb-2">&nbsp;</p>

                                                <p>&nbsp;</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                    </div>
                </div>
            </div>
        </section>


        <section class="pricing-section section-padding section-bg-light" id="section_5" data-aos="fade-up" data-aos-duration="1000">
            <div class="container">
                <div class="row">
        
                    <div class="col-lg-8 col-12 mx-auto" data-aos="fade-up" data-aos-delay="100">
                        <h2 class="text-center mb-4">Assinatura VIP</h2>
                    </div>
        
                    <div class="col-lg-6 col-12" data-aos="zoom-in-up" data-aos-delay="200">
                        <div class="pricing-thumb">
                            <div class="d-flex">
                                <div>
                                    <h3><small>6 Meses</small> R$280</h3>
                                    <p>Benefícios de ser VIP:</p>
                                </div>
                                <p class="pricing-tag ms-auto">Economize <span>50%</span></p>
                            </div>
        
                            <ul class="pricing-list mt-3">
                                <li class="pricing-list-item">Rede social Exclusiva Desiree Club</li>
                                <li class="pricing-list-item">Chat com casais e solteiros</li>
                                <li class="pricing-list-item">Postagem de imagens e vídeos</li>
                                <li class="pricing-list-item">Radar, Grupos e Contos</li>
                            </ul>
        
                            <a class="link-fx-1 color-contrast-higher mt-4" href="ticket.html">
                                <span>Assinar</span>
                                <svg class="icon" viewBox="0 0 32 32" aria-hidden="true">
                                    <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="16" cy="16" r="15.5"></circle>
                                        <line x1="10" y1="18" x2="16" y2="12"></line>
                                        <line x1="16" y1="12" x2="22" y2="18"></line>
                                    </g>
                                </svg>
                            </a>
                        </div>
                    </div>
        
                    <div class="col-lg-6 col-12 mt-4 mt-lg-0" data-aos="zoom-in-up" data-aos-delay="400">
                        <div class="pricing-thumb">
                            <div class="d-flex">
                                <div>
                                    <h3><small>1 Mês</small> R$60</h3>
                                    <p>Benefícios de ser VIP:</p>
                                </div>
                            </div>
        
                            <ul class="pricing-list mt-3">
                                <li class="pricing-list-item">Rede social Exclusiva Desiree Club</li>
                                <li class="pricing-list-item">Chat com casais e solteiros</li>
                                <li class="pricing-list-item">Postagem de imagens e vídeos</li>
                                <li class="pricing-list-item">Radar, Grupos e Contos</li>
                            </ul>
        
                            <a class="link-fx-1 color-contrast-higher mt-4" href="ticket.html">
                                <span>Assinar</span>
                                <svg class="icon" viewBox="0 0 32 32" aria-hidden="true">
                                    <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="16" cy="16" r="15.5"></circle>
                                        <line x1="10" y1="18" x2="16" y2="12"></line>
                                        <line x1="16" y1="12" x2="22" y2="18"></line>
                                    </g>
                                </svg>
                            </a>
                        </div>
                    </div>
        
                </div>
            </div>
        </section>
        
        
        
        <section class="reserva-section section-padding section-bg-dark" id="reserva">
            <div class="container text-center text-white">
                <h1 class="text-danger fw-bold text-uppercase mt-5">Agendar Reserva</h1>
                
                <div class="position-relative mt-4">
                <img src="{{ asset('images/background.jpg') }}" class="img-fluid w-100" style="filter: brightness(20%);">
                <div class="position-absolute top-50 start-50 translate-middle text-center">
                    <button class="btn btn-danger fw-bold text-uppercase px-4 py-2">Faça sua reserva</button>
                </div>
            </div>
            
                <div class="row mt-5">
                    <div class="col-md-6 col-lg-3 mb-4">
                        <div class="bg-dark text-white p-4 rounded">
                            <i class="bi bi-person-fill fs-2 text-danger"></i>
                            <h5 class="text-uppercase mt-3">Solteiras</h5>
                            <p>Entrada Free<br> Dose Cortesia<br> Serviço de Carona</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3 mb-4">
                        <div class="bg-dark text-white p-4 rounded">
                            <i class="bi bi-people-fill fs-2 text-danger"></i>
                            <h5 class="text-uppercase mt-3">4 Amigas</h5>
                            <p>Ganha 1 litro de Smirnoff com acompanhamento</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3 mb-4">
                        <div class="bg-dark text-white p-4 rounded">
                            <i class="bi bi-calendar-event-fill fs-2 text-danger"></i>
                            <h5 class="text-uppercase mt-3">Aniversário</h5>
                            <p>Promoções Especiais<br> Consulte as condições e valores</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3 mb-4">
                        <div class="bg-dark text-white p-4 rounded">
                            <i class="bi bi-calendar-event-fill fs-2 text-danger"></i>
                            <h5 class="text-uppercase mt-3">Eventos</h5>
                            <p>Venha fazer seu evento com o que há de melhor</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="faq-section section-padding section-bg-light" id="faq">
            <div class="container">
                <div class="row">
                    <h2 class="text-center mb-4">Perguntas Frequentes</h2>
                    <div class="accordion accordion-flush" id="accordionFlushExample">
                        <div class="accordion-item">
                          <h2 class="accordion-header" id="flush-headingOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                              Como participar do bate-papo da Desiree?
                            </button>
                          </h2>
                          <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate the <code>.accordion-flush</code> class. This is the first item's accordion body.</div>
                          </div>
                        </div>
                        <div class="accordion-item">
                          <h2 class="accordion-header" id="flush-headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                             Homem não acompanhado pode entrar?
                            </button>
                          </h2>
                          <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate the <code>.accordion-flush</code> class. This is the second item's accordion body. Let's imagine this being filled with some actual content.</div>
                          </div>
                        </div>
                        <div class="accordion-item">
                          <h2 class="accordion-header" id="flush-headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                             Qual o valor da entrada?
                            </button>
                          </h2>
                          <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate the <code>.accordion-flush</code> class. This is the third item's accordion body. Nothing more exciting happening here in terms of content, but just filling up the space to make it look, at least at first glance, a bit more representative of how this would look in a real-world application.</div>
                          </div>
                        </div>
                      </div>
                </div>
                <div class="row mt-5">
                    <div class="col-12">
                        <h3 class="text-center mb-3">Envie sua Pergunta</h3>
                        <form class="d-flex">
                            <input type="text" class="form-control me-2" id="nome" placeholder="Seu nome">
                            <input type="text" class="form-control me-2" id="pergunta" placeholder="Digite sua pergunta">
                            <button type="submit" class="btn btn-primary">Enviar</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <section class="estrutura-section paddig section-bg-dark" id="estrutura">
            <div class="container py-5">               
                <div class="row">
                    <h2 class="text-center mb-4" data-aos="fade-up">Estrutura</h2>
        
                    <div class="col-md-4">
                        <h3><i class="bi bi-fire text-warning"></i> Primeiro Piso</h3>
                        <h5>Casais e Solteiras</h5>
                        <ul class="list-unstyled">
                            <li data-aos="fade-up" data-aos-delay="100"><i class="bi bi-fire text-warning"></i> Sala B.D.S.M</li>
                            <li data-aos="fade-up" data-aos-delay="200"><i class="bi bi-fire text-warning"></i> Quartos privativos</li>
                            <li data-aos="fade-up" data-aos-delay="300"><i class="bi bi-fire text-warning"></i> Baladinha para casais</li>
                            <li data-aos="fade-up" data-aos-delay="400"><i class="bi bi-fire text-warning"></i> Dark room</li>
                            <li data-aos="fade-up" data-aos-delay="500"><i class="bi bi-fire text-warning"></i> Cabines glory hole</li>
                            <li data-aos="fade-up" data-aos-delay="600"><i class="bi bi-fire text-warning"></i> Quarto aquário</li>
                            <li data-aos="fade-up" data-aos-delay="700"><i class="bi bi-fire text-warning"></i> Cabine glory coletiva</li>
                            <li data-aos="fade-up" data-aos-delay="800"><i class="bi bi-fire text-warning"></i> Camão coletivo</li>
                            <li data-aos="fade-up" data-aos-delay="900"><i class="bi bi-fire text-warning"></i> Cabines de silhueta</li>
                        </ul>
                    </div>
        
                    <div class="col-md-4">
                        <h3><i class="bi bi-fire text-warning"></i> Segundo Piso</h3>
                        <h5>Área Mista</h5>
                        <ul class="list-unstyled">
                            <li data-aos="fade-up" data-aos-delay="100"><i class="bi bi-fire text-warning"></i> Quartos abertos</li>
                            <li data-aos="fade-up" data-aos-delay="200"><i class="bi bi-fire text-warning"></i> Sala Cuckold</li>
                            <li data-aos="fade-up" data-aos-delay="300"><i class="bi bi-fire text-warning"></i> Canines glory hole</li>
                            <li data-aos="fade-up" data-aos-delay="400"><i class="bi bi-fire text-warning"></i> Cabine glory coletiva</li>
                            <li data-aos="fade-up" data-aos-delay="500"><i class="bi bi-fire text-warning"></i> Dark room</li>
                        </ul>
                    </div>
        
                    <div class="col-md-4">
                        <h3><i class="bi bi-fire text-warning"></i> Terceiro Piso</h3>
                        <h5>Área Mista</h5>
                        <ul class="list-unstyled">
                            <li data-aos="fade-up" data-aos-delay="100"><i class="bi bi-fire text-warning"></i> Quartos abertos</li>
                            <li data-aos="fade-up" data-aos-delay="200"><i class="bi bi-fire text-warning"></i> Sala Cuckold</li>
                            <li data-aos="fade-up" data-aos-delay="300"><i class="bi bi-fire text-warning"></i> Canines glory hole</li>
                            <li data-aos="fade-up" data-aos-delay="400"><i class="bi bi-fire text-warning"></i> Cabine glory coletiva</li>
                            <li data-aos="fade-up" data-aos-delay="500"><i class="bi bi-fire text-warning"></i> Dark room</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
        

p
        <section class="contact-section section-padding" id="section_6">
            <div class="container">
                <div class="row">

                    <div class="col-lg-8 col-12 mx-auto">
                        <h2 class="text-center mb-4">Vamos Conversar</h2>

                        <nav class="d-flex justify-content-center">
                            <div class="nav nav-tabs align-items-baseline justify-content-center" id="nav-tab"
                                role="tablist">
                                <button class="nav-link active" id="nav-ContactForm-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-ContactForm" type="button" role="tab"
                                    aria-controls="nav-ContactForm" aria-selected="false">
                                    <h5>Contato</h5>
                                </button>

                                <button class="nav-link" id="nav-ContactMap-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-ContactMap" type="button" role="tab"
                                    aria-controls="nav-ContactMap" aria-selected="false">
                                    <h5>Localização</h5>
                                </button>
                            </div>
                        </nav>

                        <div class="tab-content shadow-lg mt-5" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-ContactForm" role="tabpanel"
                                aria-labelledby="nav-ContactForm-tab">
                                <form class="custom-form contact-form mb-5 mb-lg-0" action="#" method="post"
                                    role="form">
                                    <div class="contact-form-body">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-12">
                                                <input type="text" name="contact-name" id="contact-name"
                                                    class="form-control" placeholder="Nome Completo" required>
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-12">
                                                <input type="email" name="contact-email" id="contact-email"
                                                    pattern="[^ @]*@[^ @]*" class="form-control"
                                                    placeholder="Email" required>
                                            </div>
                                        </div>

                                        <input type="tel" name="telefone" id="telefone"
                                            class="form-control" placeholder="Telefone" required>

                                        <textarea name="contact-message" rows="3" class="form-control"
                                            id="contact-message" placeholder="Mensagem"></textarea>

                                        <div class="col-lg-4 col-md-10 col-8 mx-auto">
                                            <button type="submit" class="form-control">Enviar Mensagem</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane fade" id="nav-ContactMap" role="tabpanel"
                                aria-labelledby="nav-ContactMap-tab">

                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d57663.3866269571!2d-49.43980107832032!3d-25.406093800000004!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94dd1e2b250ba61d%3A0x97a32a0375d1e3cc!2sDesiree%20Swing%20Club!5e0!3m2!1spt-PT!2sbr!4v1742488308987!5m2!1spt-PT!2sbr" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                               
                                
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </main>


    <footer class="site-footer">
        <div class="site-footer-top">
            <div class="container">
                <div class="row">

                    <div class="col-lg-6 col-12">
                        <h2 class="text-white mb-lg-0">Desiree Swing Club</h2>
                    </div>

                    <div class="col-lg-6 col-12 d-flex justify-content-lg-end align-items-center">
                        <ul class="social-icon d-flex justify-content-lg-end">
                           
                            <li class="social-icon-item">
                                <a href="http://facebook.com/profile.php?id=61559397827068" class="social-icon-link">
                                    <span class="bi-facebook"></span>
                                </a>
                            </li>

                            <li class="social-icon-item">
                                <a href="#" class="social-icon-link">
                                    <span class="bi-instagram"></span>
                                </a>
                            </li>

                            <li class="social-icon-item">
                                <a href="#" class="social-icon-link">
                                    <span class="bi-youtube"></span>
                                </a>
                            </li>

                            
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">

                <div class="col-lg-6 col-12 mb-4 pb-2">
                    <h5 class="site-footer-title mb-3">Menu</h5>

                    <ul class="site-footer-links">
                        <li class="site-footer-link-item">
                            <a href="#" class="site-footer-link">Principal</a>
                        </li>

                        <li class="site-footer-link-item">
                            <a href="#" class="site-footer-link">Sobre</a>
                        </li>

                        <li class="site-footer-link-item">
                            <a href="#" class="site-footer-link">Atrações</a>
                        </li>

                        <li class="site-footer-link-item">
                            <a href="#" class="site-footer-link">Programação</a>
                        </li>

                        <li class="site-footer-link-item">
                            <a href="#" class="site-footer-link">Assinatura VIP</a>
                        </li>

                        <li class="site-footer-link-item">
                            <a href="#" class="site-footer-link">Contato</a>
                        </li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 col-12 mb-4 mb-lg-0">
                    <h5 class="site-footer-title mb-3">Duvidas?</h5>

                    <p class="text-white d-flex mb-1">
                        <a href="tel: (41)9.9724-8804" class="site-footer-link">
                            (41)9.9724-8804
                        </a>
                    </p>

                    <p class="text-white d-flex">
                        <a href="mailto:suporte@desireeclub.com.br" class="site-footer-link">
                            suporte@desireeclub.com.br
                        </a>
                    </p>
                </div>

                <div class="col-lg-3 col-md-6 col-11 mb-4 mb-lg-0 mb-md-0">
                    <h5 class="site-footer-title mb-3">Localização</h5>

                    <p class="text-white d-flex mt-3 mb-2">
                        Rua Brasilio Cuman, 2100. Curitiba, Paraná. Brasil</p>

                    <a class="link-fx-1 color-contrast-higher mt-3" href="#">
                        <span>Mapa</span>
                        <svg class="icon" viewBox="0 0 32 32" aria-hidden="true">
                            <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="16" cy="16" r="15.5"></circle>
                                <line x1="10" y1="18" x2="16" y2="12"></line>
                                <line x1="16" y1="12" x2="22" y2="18"></line>
                            </g>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <div class="site-footer-bottom">
            <div class="container">
                <div class="row">

                    <div class="col-lg-3 col-12 mt-5">
                        <p class="copyright-text">Copyright © 2025 Desiree Swing Club</p>
                        <p class="copyright-text">Desenvolvido por: <a href="https://agencianovaz.com.br">Novaz</a></p>
                    </div>

                    <div class="col-lg-8 col-12 mt-lg-5">
                        <ul class="site-footer-links">
                            <li class="site-footer-link-item">
                                <a href="#" class="site-footer-link">Regras da casa</a>
                            </li>

                            <li class="site-footer-link-item">
                                <a href="#" class="site-footer-link">Regras de Vestimenta</a>
                            </li>

                            <li class="site-footer-link-item">
                                <a href="#" class="site-footer-link">Deixe sua Sugestão</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>


    <!-- JAVASCRIPT FILES -->
    <script src="js/jquery.min.js"></script>
    {{-- <script src="js/bootstrap.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="js/jquery.sticky.js"></script>
    <script src="js/click-scroll.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000, // tempo padrão da animação
            once: true,     // anima apenas na primeira vez que aparece
        });
    </script>
    

    <script src="js/custom.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
</body>
</html>