<?php
require('top.inc.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agric Resources</title>

    <style>
        /* Custom styles */
        #blog .blog-bx {
            margin-bottom: 30px;
        }
        #blog .blog-details a {
            text-decoration: none;
            font-size: 14px;
            color: #000;
            font-weight: 700;
            position: relative;
            transition: 0.3s;
        }
        #blog .blog-details a::after {
            content: "";
            width: 50px;
            height: 1px;
            background-color: #000;
            position: absolute;
            top: 50%;
            right: -60px;
            transition: 0.3s;
        }
        #blog .blog-details a:hover {
            color: #0d6efd;
        }
        #blog .blog-details a:hover::after {
            background-color: #0d6efd;
        }
        .modal-body .link-list {
            list-style-type: none;
            padding-left: 0;
        }
        .modal-body .link-list li {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div class="content pb-0">
	<div class="orders">
	   <div class="row">
		  <div class="col-xl-12">
			 <div class="card">
				<div class="card-body">
				   <h1 class="box-title">learn more about agriculture  </h1>
                
			  <div class="card-body">  
              <h4 class="box-title">Get link to various Advisory services and extensions</h4>     
        <div id="blog">
            <div class="row blog-bx">
                <div class="col-md-4">
                    <img src="images/5.jpeg" alt="Farm" class="img-fluid">
                </div>
                <div class="col-md-8 blog-details">
                    <h2>Find Useful Tips and Tricks to Better Your Farming Experience</h2>
                    <p>National Agriculture Advisory Services (NAADS) is one of the statutory semi-autonomous bodies in the Ministry of Agriculture, Animal Industry and Fisheries (MAAIF); established in 2001 by an Act of Parliament (NAADS Act 2001) to specifically facilitate efficient and effective delivery of agricultural advisory services for enhanced production and productivity.</p>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#infoModal" 
                       data-title="Agricultural Resources" 
                       data-description="Explore these valuable resources to enhance your agricultural knowledge and practices."
                       data-links='[
                           {"title": "NAADS Official Website", "url": "https://naads.or.ug/about-us/"},
                           {"title": "FAO Uganda", "url": "http://www.fao.org/uganda/en/"},
                           {"title": "Uganda Ministry of Agriculture", "url": "https://www.agriculture.go.ug/"}
                       ]'>FIND OUT MORE</a>
                </div>
            </div>
            
            <!-- Add more blog entries here -->
            <div class="row blog-bx">
                <div class="col-md-4">
                    <img src="images/money.jpg" alt="Farm" class="img-fluid" >
                </div>
                <div class="col-md-8 blog-details">
                <h1>Get to know about market prices </h1>
                <p>The market information portal is designed as a one step area for “Information for Trade” it give trade users the opportunity to explore daily, weekly, month and quarterly prices and weather information reports.</p>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#infoModal" 
                       data-title="Agricultural Resources" 
                       data-description="Explore these valuable resources to enhance your product pricing experience"
                       data-links='[
                            {"title": "Info Trade ", "url": "https://app.infotradeconnect.com/dashboard/index"},
                           {"title": "Agromarket Official Website", "url": "http://www.agromarketday.com/markets/12-owino-market"},
                           {"title": "Farmginafrica Markets WebPrices", "url": "https://farmgainafrica.org/market-data/uganda-market-prices"},
                           {"title": "Agric Care", "url": "https://www.agric-care.com/kampalamarket"}
                       ]'>FIND OUT MORE</a>
                </div>
            </div>
            
            <div class="row blog-bx">
                <div class="col-md-4">
                    <img src="" alt="Farm" class="img-fluid">
                </div>
                <div class="col-md-8 blog-details">
                    <h2>Find Useful Tips and Tricks to Better Your Farming Experience</h2>
                    <p>National Agriculture Advisory Services (NAADS) is one of the statutory semi-autonomous bodies in the Ministry of Agriculture, Animal Industry and Fisheries (MAAIF); established in 2001 by an Act of Parliament (NAADS Act 2001) to specifically facilitate efficient and effective delivery of agricultural advisory services for enhanced production and productivity.</p>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#infoModal" 
                       data-title="Agricultural Resources" 
                       data-description="Explore these valuable resources to enhance your agricultural knowledge and practices."
                       data-links='[
                           {"title": "NAADS Official Website", "url": "https://naads.or.ug/about-us/"},
                           {"title": "FAO Uganda", "url": "http://www.fao.org/uganda/en/"},
                           {"title": "Uganda Ministry of Agriculture", "url": "https://www.agriculture.go.ug/"}
                       ]'>FIND OUT MORE</a>
                </div>
            </div>
            
        </div>
    </div>

    <!-- Bootstrap Modal -->
    <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="infoModalLabel">Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="modalDescription"></p>
                    <h6>Useful Links:</h6>
                    <ul id="modalLinks" class="link-list"></ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
                       

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <script>
        var infoModal = document.getElementById('infoModal')
        infoModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget
            var title = button.getAttribute('data-title')
            var description = button.getAttribute('data-description')
            var links = JSON.parse(button.getAttribute('data-links'))
            
            var modalTitle = infoModal.querySelector('.modal-title')
            var modalDescription = infoModal.querySelector('#modalDescription')
            var modalLinks = infoModal.querySelector('#modalLinks')
            
            modalTitle.textContent = title
            modalDescription.textContent = description
            
            // Clear previous links
            modalLinks.innerHTML = ''
            
            // Add new links
            links.forEach(function(link) {
                var li = document.createElement('li')
                var a = document.createElement('a')
                a.href = link.url
                a.target = '_blank'
                a.textContent = link.title
                a.className = 'btn btn-outline-primary btn-sm'
                li.appendChild(a)
                modalLinks.appendChild(li)
            })
        })
    </script>
</body>
</html>


<?php
require('footer.inc.php');
?>