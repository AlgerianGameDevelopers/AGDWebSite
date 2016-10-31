var iOS = !!navigator.platform && /iPad|iPhone|iPod/.test(navigator.platform);
var ff = navigator.userAgent.indexOf('Firefox') > 0;
var tap = ('ontouchstart' in window || navigator.msMaxTouchPoints) ? 'touchstart' : 'mousedown';
if (iOS) document.body.classList.add('iOS');
var fireworks = (function() {
  var getFontSize = function() {
    return parseFloat(getComputedStyle(document.documentElement).fontSize);
  }
  var canvas = document.querySelector('.fireworks');
  var ctx = canvas.getContext('2d');
  //var numberOfParticules = 24;
  //var distance = 600;
  var x = 0;
  var y = 0;
  var animations = [];
  var setCanvasSize = function() {
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
  }
  var updateCoords = function(e) {
    x = e.clientX || e.touches[0].clientX;
    y = e.clientY || e.touches[0].clientY;
  }/*
  var colors = ['#FF324A', '#31FFA6', '#206EFF', '#FFFF99'];*/
  var createCircle = function(x,y) {
    var p = {};
    p.x = x;
    p.y = y;
    //p.color = colors[anime.random(0, colors.length - 1)];
    p.color = '#fff';
    p.radius = 0;
    p.alpha = 1;
    p.lineWidth = 30;
    p.draw = function() {
      ctx.globalAlpha = p.alpha;
      ctx.beginPath();
      ctx.arc(p.x, p.y, p.radius, 0, 2 * Math.PI, true);
      ctx.lineWidth = p.lineWidth;
      ctx.strokeStyle = p.color;
      ctx.stroke();
      ctx.globalAlpha = 1;
    }
    return p;
  }
  /*var createParticule = function(x,y) {
    var p = {};
    p.x = x;
    p.y = y;
    p.color = colors[anime.random(0, colors.length - 1)];
    p.radius = anime.random(getFontSize(), getFontSize() * 2);
    p.draw = function() {
      ctx.beginPath();
      ctx.arc(p.x, p.y, p.radius, 0, 2 * Math.PI, true);
      ctx.fillStyle = p.color;
      ctx.fill();
    }
    return p;
  }
  var createParticles = function(x,y) {
    var particules = [];
    for (var i = 0; i < numberOfParticules; i++) {
      var p = createParticule(x, y);
      particules.push(p);
    }
    return particules;
  }*/
  var removeAnimation = function(animation) {
    var index = animations.indexOf(animation);
    if (index > -1) animations.splice(index, 1);
  }
  var animateParticules = function(x, y) {
    setCanvasSize();
    //var particules = createParticles(x, y);
    var circle = createCircle(x, y);
    /*var particulesAnimation = anime({
      targets: particules,
      x: function(p) { return p.x + anime.random(-distance, distance); },
      y: function(p) { return p.y + anime.random(-distance, distance); },
      radius: 0,
      duration: function() { return anime.random(1200, 1800); },
      easing: 'easeOutExpo',
      complete: removeAnimation
    });*/
    var circleAnimation = anime({
      targets: circle,
      radius: 200,
      lineWidth: 0,
      stroke: [20,0],
      alpha: {
        value: 0,
        easing: 'linear',
        duration: 800
      },
      duration: 2000,
      easing: 'easeOutExpo',
      complete: removeAnimation
    });
    //animations.push(particulesAnimation);
    animations.push(circleAnimation);
  }
  var mainLoop = anime({
    duration: Infinity,
    update: function() {
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      animations.forEach(function(anim) {
        anim.animatables.forEach(function(animatable) {
          animatable.target.draw();
        });
      });
    }
  });
  //document.addEventListener(tap, function(e) {
  //  updateCoords(e);
  //  animateParticules(x, y);
    //ga('send', 'event', 'Fireworks', 'Click');
  //}, false);
  window.addEventListener('resize', setCanvasSize, false);
  return {
    boom: animateParticules
  }
})();

var logoAnimation = function() {
  document.body.classList.add('ready');

  var setDashoffset = function(el) {
    var l = el.getTotalLength();
    el.setAttribute('stroke-dasharray', l);
    return [l,0];
  }

  /*var dotDis = anime({
    targets: '#boom-cir',
    opacity: [1,0],
    duration: 800,
    easing: 'easeOutCirc',
    autoplay: false
  });*/

  var dotB = anime({
    targets: '#boom-cir',
    //transform: ['translate(0 -204)', 'translate(0 0)'],
    opacity: [0,0],
    duration: 100,
    //elasticity: 300,
    //easing: 'easeOutElastic',
    //complete: dotDis.play
  });

  var boom = anime({
    duration: 800,
    complete: function(a) {
      var dot = dotB.animatables[0].target.getBoundingClientRect();
      var pos = {x: dot.left + (dot.width / 2), y: dot.top + (dot.height / 2)}
      fireworks.boom(pos.x, pos.y);
    }
  });

  var letters = anime({
    targets: '#lines .line-w',
    strokeDashoffset: {
      value: setDashoffset,
      duration: 1500,
      easing: 'easeOutQuad'
    },
    transform: ['translate(0 80)', 'translate(0 0)'],
    delay: function(el, i) {
      return 1500 + (i * 80)
    },
    duration: 1500
  });

  var logo = anime({
    targets: '#lines .line-logo',
    strokeDashoffset: {
      value: setDashoffset,
      duration: 1000,
      easing: 'easeOutQuad'
    },
    //transform: ['translate(0 30)', 'translate(0 0)'],
    //rotate: [180, 0],
    delay: 800,
    duration: 1500
  });

  var fills = anime({
    targets: '#fills',
    opacity: [0, 1],
    delay: function(el, i, l) {
      var mid = l/2;
      var index = (i - mid) > mid ? 0 : i;
      var delay = Math.abs(index - mid);
      return (letters.duration - 500) + (delay * 30);
    },
    duration: 2000,
    easing: 'linear'
  });

  var bg = anime({
    targets: 'body',
    background: ['#22b076','#ffffff'],
    duration: 2000,
    delay: letters.duration - 500,
  });
  
}
window.onload = logoAnimation;