// yoga00/frontend/common/homepage.js

function generaHomepage() {
  let homepageHMTL = `

    <!-- HERO SECTION -->
    <section class="hero text-center text-white d-flex align-items-center justify-content-center">
        <div class="container">
            <h1 class="display-3 fw-bold mb-4">White Yoga</h1>
            <p class="lead mb-5">Trova equilibrio, forza e serenità attraverso lo yoga</p>
            <a href="" class="btn btn-lg btn-accent">Inizia ora</a>
        </div>
    </section>

    <!-- BENEFICI DELLO YOGA -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5 text-primary">I benefici dello yoga</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body text-center p-4">
                            <div class="icon-circle mb-4">
                                <i class="fas fa-heartbeat fa-3x"></i>
                            </div>
                            <h5 class="card-title">Benessere fisico</h5>
                            <p class="card-text">Migliora flessibilità, forza e postura. Riduce dolori cronici e tensioni muscolari.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body text-center p-4">
                            <div class="icon-circle mb-4">
                                <i class="fas fa-brain fa-3x"></i>
                            </div>
                            <h5 class="card-title">Mente serena</h5>
                            <p class="card-text">Riduce stress e ansia. Migliora concentrazione, sonno e chiarezza mentale.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body text-center p-4">
                            <div class="icon-circle mb-4">
                                <i class="fas fa-spa fa-3x"></i>
                            </div>
                            <h5 class="card-title">Equilibrio interiore</h5>
                            <p class="card-text">Coltiva consapevolezza, pazienza e connessione tra corpo, mente e respiro.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CONTATTI -->
    <section class="bg-light py-5">
        <div class="container">
            <h2 class="text-center mb-5 text-primary">Contattaci</h2>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="row text-center">
                        <div class="col-sm-4 mb-4">
                            <i class="fas fa-map-marker-alt fa-2x text-primary mb-3"></i>
                            <p>Lungo Dora<br>10010 Torino, Italia</p>
                        </div>
                        <div class="col-sm-4 mb-4">
                            <i class="fas fa-phone fa-2x text-primary mb-3"></i>
                            <p>+39 011 1234567</p>
                        </div>
                        <div class="col-sm-4 mb-4">
                            <i class="fas fa-envelope fa-2x text-primary mb-3"></i>
                            <p>info@whiteyoga.it</p>
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <a href="" class="btn btn-primary btn-lg">Accedi all'area riservata</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
  `;


  // Inietto l'thml della homepage appena creata
  document.getElementById('page-content').innerHTML = homepageHMTL;
}
