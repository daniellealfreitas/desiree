
  (function ($) {
  
  "use strict";

    // MENU
    $('.navbar-collapse a').on('click',function(){
      $(".navbar-collapse").collapse('hide');
    });
    
    // CUSTOM LINK
    $('.smoothscroll').click(function(){
      var el = $(this).attr('href');
      var elWrapped = $(el);
      var header_height = $('.navbar').height();
  
      scrollToDiv(elWrapped,header_height);
      return false;
  
      function scrollToDiv(element,navheight){
        var offset = element.offset();
        var offsetTop = offset.top;
        var totalScroll = offsetTop-navheight;
  
        $('body,html').animate({
        scrollTop: totalScroll
        }, 300);
      }
    });
  
  })(window.jQuery);




document.addEventListener("DOMContentLoaded", function() {
  // Criando um container para o background animado
  let bgContainer = document.createElement("div");
  bgContainer.style.position = "absolute";
  bgContainer.style.top = "0";
  bgContainer.style.left = "0";
  bgContainer.style.width = "100%";
  bgContainer.style.height = "100%";
  bgContainer.style.overflow = "hidden";
  bgContainer.style.zIndex = "-1";
  bgContainer.style.background = "radial-gradient(circle, rgba(63,94,251,0.2) 0%, rgba(252,70,107,0.3) 100%)";
  document.querySelector("#section_2").appendChild(bgContainer);
  
  // Criando partículas animadas
  for (let i = 0; i < 20; i++) {
      let particle = document.createElement("div");
      particle.style.position = "absolute";
      particle.style.width = "20px";
      particle.style.height = "20px";
      particle.style.background = "rgba(255, 255, 255, 0.5)";
      particle.style.borderRadius = "50%";
      particle.style.top = `${Math.random() * 100}%`;
      particle.style.left = `${Math.random() * 100}%`;
      bgContainer.appendChild(particle);

      gsap.to(particle, {
          y: "+=50",
          duration: 2 + Math.random() * 3,
          repeat: -1,
          yoyo: true,
          ease: "power1.inOut"
      });
  }
  
  // Efeito Parallax ao rolar a página
  gsap.to(bgContainer, {
      backgroundPositionY: "+=200px",
      scrollTrigger: {
          trigger: "#section_2",
          start: "top bottom",
          end: "bottom top",
          scrub: true
      }
  });
});
