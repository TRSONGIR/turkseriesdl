var $circleBorder = $('.countdown__icon__circle').get(0);
var $countdown = $('.countdown');

var length = $circleBorder.getTotalLength();
var counter = {
  var: 10
};

var tl = new TimelineMax({
  delay: 0.75,
  repeatDelay: 1,
  repeat: -1,
});

tl.play();

tl.set($countdown, {
    scale: 0,
  }).to($countdown, 0.4, {
    scale: 1,
    ease: Back.easeOut.config(1.4),
  }).to($circleBorder, 10, {
    strokeDashoffset: length,
    stroke: '#FB7593',
    ease: Power0.easeNone
  }).to(counter, 10, {
    var: 0,
    onUpdate: function() {
      $('.countdown__number').html(Math.ceil(counter.var));
    },
    ease: Power0.easeNone
  }, '-= 10')
  .to($countdown, 0.4, {
    scale: 0,
    ease: Back.easeIn.config(1.4),
    onComplete: function() {
      $('.countdown__number').html('10');
    }
  });