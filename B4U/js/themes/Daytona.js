var rollingstart = document.createElement('audio');
if (sessionStorage.resumetime) {
	rollingstart.currentTime = sessionStorage.resumetime;
}
rollingstart.src = 'https://prettyboytellem.com/cdn/music/king-of-speed.ogg';
rollingstart.loop = true;
rollingstart.play();

window.addEventListener('unload', function() {
	sessionStorage.resumetime = rollingstart.currentTime;
})
