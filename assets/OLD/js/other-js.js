
$('.btnpickup').click(function () {
  $('.btnpickup').removeClass('red');
  $(this).addClass('red');
});

var bulky = true;
var notbulky = false;
// $('.btnpickup.pur1').click(function () {
//   if (!bulky && notbulky) {
//     $('.form-pickup-bulky').animate({
//       height: "toggle"
//     }, 500, function() {
//       $('.form-pickup').animate({
//         height: "toggle"
//       }, 500, function() {
//         bulky = true;
//       });
//     });
//   }
//   notbulky=false;
// });
$('.btnpickup.pur2').click(function () {
  // if (bulky && !notbulky) {
  //   $('.form-pickup').animate({
  //     height: "toggle"
  //   }, 500, function() {
  //     $('.form-pickup-bulky').animate({
  //       height: "toggle"
  //     }, 500, function() {
  //       bulky = false;
  //     });
  //   });
  // }
  // notbulky=true;

  $('.form-pickup-bulky').animate({
        height: "toggle"
      }, 500, function() {
        bulky = false;
      });
});