var leng = 1000;
var step = 4;
var opa = 255;
var results = [];

async function updateResults() {

  if (!isFaceDetectionModelLoaded()) {
    return
  }

  const inputImgEl = $('#inputImg').get(0);
  const options = getFaceDetectorOptions();
  results = await faceapi.detectAllFaces(inputImgEl, options).withFaceLandmarks();
  const canvas = $('#overlay').get(0);
  faceapi.matchDimensions(canvas, inputImgEl)

  //Calculate scale value
  if(inputImgEl.naturalWidth < 1023)
    scaleMatrix = inputImgEl.naturalWidth/1023;
  scale = screenW/inputImgEl.naturalWidth;
  $("#scale").text(scale+'---'+scaleMatrix);

  //Adjust parameter boundary
  for (var i = 0; i < boundeye.length; i++) {
    boundeye[i].x *= scaleMatrix;
    boundeye[i].y *= scaleMatrix;
  }

  var _img = null;
  var _img = new fabric.Image(inputImgEl, {
    width: inputImgEl.naturalWidth*scale,
    height: inputImgEl.naturalHeight*scale,
    left: 0,
    top: 0,
    selectable: false,

  });
  kanvas.add(_img);

  setTimeout(function(){
    return;
    eyeClear = [], eyeClear2 = [];
    for (var i = 0; i < results[0].landmarks.positions.length; i++) {
      var _x = results[0].landmarks.positions[i]._x;
      var _y = results[0].landmarks.positions[i]._y;
      
      if(i>= 17 && i<=26){
        _y += 7/(scale/scaleMatrix);
      }
      //-----------------------------------EYE DATA 2--------------------------
      if(i == 22){
        eyeClear2.push({x: _x+(-1*boundeye[6].x), y: _y+boundeye[6].y});
      }

      if(i >= 22 && i <= 26){
        eyeClear2.push({x: _x, y: _y});
        eyeClear2.push({x: _x+(-1*boundeye[6-(i-21)].x), y: _y+boundeye[6-(i-21)].y});
        eyeClear2.push({x: _x+boundeye[6-(i-21)].x, y: _y+(-1*boundeye[6-(i-21)].y)});
      }

      if(i == 26){
        eyeClear2.push({x: _x+(-1*boundeye[0].x), y: _y+boundeye[0].y});
      }
      //-----------------------------------EYE DATA 1--------------------------
      if(i == 17){
        eyeClear.push({x: _x+boundeye[0].x, y: _y+boundeye[0].y});
      }

      if(i >= 17 && i <= 21){
        eyeClear.push({x: _x, y: _y});
        eyeClear.push({x: _x+boundeye[i-16].x, y: _y+boundeye[i-16].y});
        eyeClear.push({x: _x+(-1*boundeye[i-16].x), y: _y+(-1*boundeye[i-16].y)});
      }

      if(i == 21){
        eyeClear.push({x: _x+boundeye[6].x, y: _y+boundeye[6].y});
      }
      //-----------------------------------END DATA----------------------------
      if(i>26){
        kanvas.add(new fabric.Circle({ radius: 2, fill: '#f0f', top: _y*scale, left: _x*scale }).set('hasControls', false));
      }
    }

    if(is_grid == true){
      kanvas.add(new fabric.Line([eyeClear[0].x*scale, eyeClear[0].y*scale, eyeClear[1].x*scale, eyeClear[1].y*scale], { 
          stroke: 'green' 
      }));
      kanvas.add(new fabric.Line([eyeClear2[0].x*scale, eyeClear2[0].y*scale, eyeClear2[1].x*scale, eyeClear2[1].y*scale], { 
          stroke: 'green' 
      }));

      for (var i = 1; i < eyeClear.length-1; i+=3) {
        j = i+3;
        j = (j>eyeClear.length-1)?eyeClear.length-1:j;
        kanvas.add(new fabric.Line([eyeClear[i].x*scale, eyeClear[i].y*scale, eyeClear[j].x*scale, eyeClear[j].y*scale], { 
            stroke: 'green' 
        }));
        kanvas.add(new fabric.Line([eyeClear2[i].x*scale, eyeClear2[i].y*scale, eyeClear2[j].x*scale, eyeClear2[j].y*scale], { 
            stroke: 'green' 
        }));
      }
      //------------------
      kanvas.add(new fabric.Line([eyeClear[0].x*scale, eyeClear[0].y*scale, eyeClear[2].x*scale, eyeClear[2].y*scale], { 
          stroke: 'green' 
      }));
      kanvas.add(new fabric.Line([eyeClear2[0].x*scale, eyeClear2[0].y*scale, eyeClear2[2].x*scale, eyeClear2[2].y*scale], { 
          stroke: 'green' 
      }));
      for (var i = 2; i < eyeClear.length-1; i+=3) {
        j = i+3;
        j = (j>eyeClear.length-1)?eyeClear.length-1:j;
        kanvas.add(new fabric.Line([eyeClear[i].x*scale, eyeClear[i].y*scale, eyeClear[j].x*scale, eyeClear[j].y*scale], { 
            stroke: 'green' 
        }));
        kanvas.add(new fabric.Line([eyeClear2[i].x*scale, eyeClear2[i].y*scale, eyeClear2[j].x*scale, eyeClear2[j].y*scale], { 
            stroke: 'green' 
        }));
      }
      //------------------
      kanvas.add(new fabric.Line([eyeClear[0].x*scale, eyeClear[0].y*scale, eyeClear[3].x*scale, eyeClear[3].y*scale], { 
          stroke: 'green' 
      }));
      kanvas.add(new fabric.Line([eyeClear2[0].x*scale, eyeClear2[0].y*scale, eyeClear2[3].x*scale, eyeClear2[3].y*scale], { 
          stroke: 'green' 
      }));
      for (var i = 3; i < eyeClear.length-1; i+=3) {
        j = i+3;
        j = (j>eyeClear.length-1)?eyeClear.length-1:j;
        kanvas.add(new fabric.Line([eyeClear[i].x*scale, eyeClear[i].y*scale, eyeClear[j].x*scale, eyeClear[j].y*scale], { 
            stroke: 'green' 
        }));
        kanvas.add(new fabric.Line([eyeClear2[i].x*scale, eyeClear2[i].y*scale, eyeClear2[j].x*scale, eyeClear2[j].y*scale], { 
            stroke: 'green' 
        }));
      }

      for (var i = 1; i < eyeClear.length-1; i+=3) {
        kanvas.add(new fabric.Line([eyeClear[i].x*scale, eyeClear[i].y*scale, eyeClear[i+1].x*scale, eyeClear[i+1].y*scale], { 
            stroke: 'green' 
        }));
        kanvas.add(new fabric.Line([eyeClear[i].x*scale, eyeClear[i].y*scale, eyeClear[i+2].x*scale, eyeClear[i+2].y*scale], { 
            stroke: 'green' 
        }));

        kanvas.add(new fabric.Line([eyeClear2[i].x*scale, eyeClear2[i].y*scale, eyeClear2[i+1].x*scale, eyeClear2[i+1].y*scale], { 
            stroke: 'green' 
        }));
        kanvas.add(new fabric.Line([eyeClear2[i].x*scale, eyeClear2[i].y*scale, eyeClear2[i+2].x*scale, eyeClear2[i+2].y*scale], { 
            stroke: 'green' 
        }));
      }
    }
    getListColor(eyeClear);
    //-------------------
    kanvas.sendBackwards(_img);
  }, 500);
  //if (withBoxes) {
    //faceapi.draw.drawDetections(canvas, resizedResults)
  //}
  //faceapi.draw.drawFaceLandmarks(canvas, resizedResults)
}

function applyFilter(){
  var ctx=kanvas.contextContainer.canvas.getContext('2d');
  var imageData=ctx.getImageData(0,0,kanvas.width,kanvas.height);
  invertColors(imageData.data, kanvas.width);
  kanvas.contextContainer.putImageData(imageData, 0, 0);
  var bs64 = kanvas.contextContainer.canvas.toDataURL();
  $('#inputImg2').attr('src', bs64);
}

function invertColors(data, width) {
  for (var i = 0; i < width*step*10; i+= step) {
    data[i] = 255; // Invert Red
    data[i+1] = 0; // Invert Green
    data[i+2] = 0; // Invert Blue
    data[i+3] = opa;
  }
}

// 16 = 1*4 + w*4
// 32 = h*4 + h*3*4

	function clearRects(data, t, l , w, h) {
      //for (var i = 0; i < data.length; i+= step) {
        //0,0
      i = 0*4;
        data[i] = 0; // Invert Red
        data[i+1] = 255; // Invert Green
        data[i+2] = 0; // Invert Blue
        data[i+3] = opa;
      //}

      //0,w
      i = (w-1)*4;
        data[i] = 0; // Invert Red
        data[i+1] = 255; // Invert Green
        data[i+2] = 0; // Invert Blue
        data[i+3] = opa;
      //h,0
      i = (h-1)*w*4;
        data[i] = 0; // Invert Red
        data[i+1] = 255; // Invert Green
        data[i+2] = 0; // Invert Blue
        data[i+3] = opa;
      //h,w
      i = (w-1)*4 + (h-1)*w*4;
        data[i] = 0; // Invert Red
        data[i+1] = 255; // Invert Green
        data[i+2] = 0; // Invert Blue
        data[i+3] = opa;
    }

    function checkColors(data, _t, _l, y, x, w, h) {
    	point = (x-_l-1)*4 + (y-_t-1)*w*4;
        //data[point] = 255; // Invert Red
        //data[point+1] = 0; // Invert Green
        //data[point+2] = 0; // Invert Blue
        //data[point+3] = opa;
        //console.log(point);
        return "#" + ("000000" + rgbToHex(data[point], data[point+1], data[point+2])).slice(-6);
    }

    async function run() {
      // load face detection and face landmark models
      await changeFaceDetector(SSD_MOBILENETV1)
      await faceapi.loadFaceLandmarkModel('/')
    }

    $(document).ready(function() {
      run()
    })

    function findRectEye(data){
    	//range[ top, left, bottom, right]
    	var range = [9999,9999,-9999,-9999];
    	for (var i = 0; i < data.length; i++) {
    		if(data[i].x < range[1])
    			range[1] = data[i].x;
    		if(data[i].x > range[3])
    			range[3] = data[i].x;
    		if(data[i].y < range[0])
    			range[0] = data[i].y;
    		if(data[i].y > range[2])
    			range[2] = data[i].y;
    	}
    	return range;
    }

  function rgbToHex(r, g, b) {
    if (r > 255 || g > 255 || b > 255)
        throw "Invalid color component";
    return ((r << 16) | (g << 8) | b).toString(16);
	}

//-------------------------------------------------------------------------
function getListColor(list){
	var rect = findRectEye(list);
	var tmpcolor1 = [];
  var tmpcolor2 = [];
	var ctx=kanvas.contextContainer.canvas.getContext('2d');
  t_crop = parseInt((rect[0]-2)*scale);
  l_crop = parseInt((rect[1]-2)*scale);
  w_crop = parseInt((rect[3] - rect[1] + 2)*scale);
  h_crop = parseInt((rect[2] - rect[0] + 2)*scale);
  var imageData = ctx.getImageData(l_crop+3, t_crop+3, w_crop, h_crop); 

  setTimeout(function(){
    for (var i = 0; i < list.length; i++) {
      //kanvas.add(new fabric.Circle({ radius: 2, fill: '#00f', top: list[i].y*scale, left: list[i].x*scale}).set('hasControls', false));
    }
    //clearRects(imageData.data, 0, 0, w_crop, h_crop);
  },500);

  setTimeout(function(){
    for (var i = 2; i < list.length-1; i+=3) {
      var colorx = checkColors(imageData.data, t_crop, l_crop, parseInt(list[i].y*scale), parseInt(list[i].x*scale), w_crop, h_crop);
      tmpcolor1.push(colorx);
    }
    for (var i = 3; i < list.length-1; i+=3) {
      var colorx = checkColors(imageData.data, t_crop, l_crop, parseInt(list[i].y*scale), parseInt(list[i].x*scale), w_crop, h_crop);
      tmpcolor2.push(colorx);
    }
    
    for (var i = 1; i < list.length-1; i+=3) {
      var rd = 8;
      var cirt = list[i].y*scale - 4;
      var cirl = list[i].x*scale - 4;
      var n_cirt = list[i+3].y*scale - 4;
      var n_cirl = list[i+3].x*scale - 4;
      var circle = new fabric.Circle({id: 'o'+i, radius: rd, top: cirt, left: cirl}).set('hasControls', false);
      var circle2 = new fabric.Circle({id: 'o'+i+'1', radius: rd, top: cirt+(n_cirt-cirt)/2, left: cirl+(n_cirl-cirl)/2}).set('hasControls', false);;
      kanvas.add(circle);
      kanvas.add(circle2);
    }
  },1000);

  setTimeout(function(){
    var j = 0;
    for (var i = 1; i < list.length-1; i+=3) {
      for (var x = 0; x < kanvas._objects.length; x++) {
        if(kanvas._objects[x].id != undefined && kanvas._objects[x].id =='o'+i){
          kanvas._objects[x].setGradient(
            'fill', {
                      x1: 8,
                      y1: 0,
                      x2: 8,
                      y2: 8,
                      colorStops: {
                        0: tmpcolor1[j],
                        1: tmpcolor2[j]
                      }
                    });
        }

        if(kanvas._objects[x].id != undefined && kanvas._objects[x].id =='o'+i+'1'){
          kanvas._objects[x].setGradient(
            'fill', {
                      x1: 8,
                      y1: 0,
                      x2: 8,
                      y2: 8,
                      colorStops: {
                        0: tmpcolor1[j],
                        1: tmpcolor2[j]
                      }
                    });
          j++;
        }
      }
    }

    //add eyebrown resource
    fabric.Image.fromURL('resource/'+eyeresource[1], function(oImg) {
      var eyescl = (list[13].x - list[1].x) / 400;
      oImg.left = results[0].landmarks.positions[22]._x*scale + 5*scale ;
      oImg.top = results[0].landmarks.positions[22]._y*scale - (oImg.height*eyescl) + 5*scale;
      oImg.scale(eyescl);
      
      oImg.clone(function(clone) {
          kanvas.add(clone.set({
              left: results[0].landmarks.positions[17]._x*scale + 5*scale,
              top: results[0].landmarks.positions[17]._y*scale - (oImg.height*eyescl) + 5*scale
          }));
      });
      
      oImg.set('flipX', true);
      kanvas.add(oImg);
      
    });
    
    kanvas.renderAll();
  	kanvas.contextContainer.putImageData(imageData, 0, 0);
    var bs64 = kanvas.contextContainer.canvas.toDataURL();
    $('#inputImg2').attr('src', bs64);

  },1500);
}

function getDotColor(){
  var ctx=kanvas.contextContainer.canvas.getContext('2d');
  var imageData = ctx.getImageData(xClick, yClick, 1, 1).data;
  $(active_color).attr('style','background-color : #'+rgbToHex(imageData[0], imageData[1], imageData[2])+' !important');
  $(active_color).attr('color', '#'+rgbToHex(imageData[0], imageData[1], imageData[2]));
  console.log('#'+rgbToHex(imageData[0], imageData[1], imageData[2])+' !important');
}

function blurEye(){
  var blur_size = 20;
  var ctx = tempas.contextContainer.canvas.getContext('2d');
  tempas.clear();
  tempas.add(new fabric.Circle({ radius: blur_size/2, fill: $(active_color).attr('color'), top: 0, left: 0 }));
  var imageData = ctx.getImageData(0, 0, blur_size, blur_size);

  blurProcess(imageData.data, blur_size);

  tempas.clear();
  tempas.contextContainer.putImageData(imageData, 0, 0);
  var bs64 = tempas.contextContainer.canvas.toDataURL();
  $('#tempImg').attr('src', bs64);

  var _tmpimgObj = new fabric.Image(tmpImgEl, {
    width: 20,
    height: 20,
    left: xClick - blur_size/2,
    top: yClick - blur_size/2,
    selectable: false
  });

  _tmpimgObj.clone(function(clone) {
    kanvas.add(clone.set('hasControls', false));
  });
}

function blurProcess(data, size){
  var list_point = [];
  var opa = 25;
  for (var h = 0; h < size; h++) {
    list_point = [];
    for (var w = 0; w < size; w++) {
      var p = w*4 + h*size*4;
      
      if(data[p] == 0 && data[p+1] == 0 && data[p+2] == 0){
        data[p+3] = 0;  
      } else {
        list_point.push(p);
      }
      
    }
    for (var i = 0; i < list_point.length/2; i++) {
      p = list_point[i];
      data[p+3] = (i+1)*opa;
    }
    var j = parseInt(list_point.length/2);
    for (var i = j+1; i < list_point.length; i++) {
      p = list_point[i];
      data[p+3] = (i-1)*opa;
    }
  }
}
