function eyeshow(e){
	is_eyeshow *= -1;
}

function setColor(e){
	active_color = e;
}

function processDown(){
	if(is_eyeshow == -1){
		getDotColor();
	} else {
		blurEye();
	}

}