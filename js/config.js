let withBoxes = true
var kanvas = null;
var scale = 1;
var scaleMatrix = 1;
var boundeye = [
  {x: -14, y: 15},
  {x: -11.5, y: -7},
  {x: 0, y: -12.8},
  {x: 0, y: -12.8},
  {x: 1.28, y: -12.8},
  {x: 10.23, y: -8.95},
  {x: 13, y: 13},
];
var eyeresource = [
  'eb1.png','eb2.png','eb3.png','eb4.png','eb5.png','eb6.png',
  'eb7.png','eb8.png','eb9.png','eb10.png','eb11.png',
  'eb1.png','eb2.png','eb3.png','eb4.png','eb5.png','eb6.png',
]
var eyeClear = [];
var eyeClear2 = [];
var is_grid = false;
var screenW = $( window ).width();
var screenH = $( window ).height();

$( document ).ready(function(){
  kanvas = new fabric.Canvas('canvas');
  $("#overlay").width(screenW-5).height(screenH-10);
  kanvas.setHeight(screenH-10);
  kanvas.setWidth(screenW-5);
  for (var i = 0; i < eyeresource.length; i++) {
    $('#eye_resource').append(
      '<a class="btn-floating btn-medium waves-effect waves-light purple lighten-3">'+
      '<img class="material-icons" src="resource/'+eyeresource[i]+'" /></a>'
    );
    
  }
  run();
})

async function run() {
  // load face detection and face landmark models
  await changeFaceDetector(SSD_MOBILENETV1)
  await faceapi.loadFaceLandmarkModel('/')
}