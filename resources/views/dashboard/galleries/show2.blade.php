{{-- @extends('adminlte::page') --}}

{{-- @section('content') --}}
    {{-- <h1 class="text-xl font-bold">{{ $client->name }} / {{ $gallery->name }}</h1>

    <a href="{{ route('dashboard.clients.galleries.create', $client) }}" class="btn btn-primary my-3">
        + Add Gallery
    </a>

    <ul>
        @foreach($gallery->folders as $folder)
            <li class="mb-2">

                <i class="fas fa-folder"></i>
                <label>{{ $folder->name }}</label>

            </li>
        @endforeach
    </ul> --}}
{{-- @stop --}}




<style>
    *{ 
  box-sizing: border-box; 
  font-family: 'Sarabun', sans-serif;
  letter-spacing: .4em;
  text-transform: uppercase;
  font-weight: 200;
  color: black;
  text-decoration: none;
}
body {
  position: relative;
  width: 100%;
  height: 100vh;
  font-size: .95em;
  margin: 0;
  overflow: hidden;
  display: grid;
  grid-template-columns: repeat(12, 1fr);
  grid-template-rows: 80px auto 90px 90px;
  grid-template-areas:
    ". n n n n n n n n n n d"
    "m c c c c c c c c c c a"
    "s c c c c c c c c c c a"
    "s . . . . . . . . . . b";
  }
.centerItem{
  display: flex !important;            /*center text in grid item*/
  align-items: center !important;      /*center text in grid item*/
  justify-content: center !important;  /*center text in grid item*/ 
}
.topBar{
  grid-area: n;
  display: flex;
  align-items: center;
  padding-right: 20px;
  animation: 5s ease-out 0s 1 fadeIn !important;
}
span{
  box-sizing: border-box;
  position: relative;
/*   box-shadow: 0px 1px 0px 0px black; */
}
span:after{
  display: block;
  position: absolute;
  width: 100%;
  content: "";
  border-bottom: 1px solid black;
  padding: 0 0 .4em 2em;  /*top right bottom left*/
  left: -3em;
  animation: 3s ease-out 0s slideInFromLeft
}
strong{
  font-weight: 800;
  -webkit-text-stroke: 1px black;
}
.leftNavBar{
  grid-area: m;
  align-self: start;
}
.leftNavBar ul{
  list-style-type: none;
  margin: 0;
  padding: 0;
  transform: rotate(180deg);
}
.leftNavBar ul li{
  writing-mode: vertical-rl;
  padding-top: 6vh;
  transform: translateX(25%);
}
li a.active{
  display: block;
  padding-left: .5em;
  border-left: 1px solid black;
  animation: 3s ease-out 0s 1 slideInFromBottomActive;
}
.aside{
  grid-area: a;
  display: grid;
  position: relative;
  top: -2px;
  align-content: end;
}
.aside i{
  color: #632C65 !important;
}
.aside a{
  display: block;
  font-size: 1em;
  letter-spacing: 0 !important;
  padding-bottom: 2em;
  filter: drop-shadow(2px 2px 2px rgba(0,0,0,.1)) 
          drop-shadow(3px 3px 3px rgba(0,0,0,.1));
/*   animation: slideInFromTop 5s ease-out 0s; */
}
.aside a:nth-child(1){
  animation: slideInFromTop 2s ease-out 0s;
}
.aside a:nth-child(2){
  animation: slideInFromTop 1s ease-out 0s;
}
.aside a:nth-child(3){
  animation: slideInFromTop .5s ease-out 0s;
}
.aside a:nth-child(4){
  animation: slideInFromTop .25s ease-out 0s;
}
.container{
  grid-area: c;
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  grid-auto-rows: 200px;
  grid-gap: 20px;
  grid-auto-flow: dense;
  overflow: auto;
  padding-right: 20px;  /*fix box-shadow on right side cutting off*/
}
/*scrollbar stuff*/
.container {
    -ms-overflow-style: none;  // IE 10+
    overflow: -moz-scrollbars-none;  // Firefox
}
.container::-webkit-scrollbar { 
    display: none;  // Safari and Chrome
}
.container>div{
  z-index: 1;
  position: relative;
  padding: 0;
  overflow: hidden;
  box-shadow: 5px 5px 10px rgba(0,0,0,.2), 
              5px 5px 25px rgba(0,0,0,.1);
  transition: all .3s ease-in-out;
}
.container div img{
  position: absolute;
  top: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  overflow: hidden;
  transition: all .6s ease-in-out;
}
.container>div, .container div img{
/*   transition: all .6s ease-in-out; */
}
/*make bIG*/
.container>div:hover, .container>div:hover img{
  transform: scale(1.05);
}
.container>div:hover img{
/*   transform: scale(1.06); */
}
.card{
  padding: 0 !important;
  width: 100%;
  height: 50%;
  bottom: -50%;
  padding: 1em !important;
  box-sizing: border-box;
  background: rgba(255,255,255,.5);
  border-top-right-radius: 0 !important;
  border-top-left-radius: 0 !important;
/*   transform: rotate(-90deg) translateX(-100%); */
/*   transform-origin: 0% 0%; */
}
.vertical{
  grid-row: span 2;
}
.horizontal{
  grid-column: span 2;
}
.big{
  grid-row: span 2;
  grid-column: span 2;
}
.footer{
  position: relative;
  z-index: 2;
  grid-column: 2/span 9;
  grid-row: 4;
  background: #4B384C;
  display: grid;
  grid-template-columns: repeat(9, 1fr);
  align-content: center;
  justify-items: start;
  box-shadow: 0 -5px 10px rgba(0,0,0,.3),
              0 -5px 20px rgba(0,0,0,.2);
}
.scroll-icon-wrapper{
  position: relative;
  grid-column: 6;
}
.footer .scroll-icon{
  display: block;
  position: relative;
  width: 20px;
  left: -50%;
  filter: invert(100%);
}
.footer-right{
  z-index: 3;
  display: flex;
  grid-column: 11/13;
  grid-row: 4;
  background: black;
  align-items: center;
  justify-content: center;
  box-shadow: 0 -5px 10px rgba(0,0,0,.3),
              0 -5px 20px rgba(0,0,0,.2);
}
.footer-right h1{
  display: block;
  position: relative;
  color: white;
  font-weight: 900;
  letter-spacing: 0;
  font-family: 'Roboto', sans-serif;
/*   border-top: 1px solid white; */
}
.footer-right h1:after{
  content: "";
  width: 100%;
  display: block;
  position: absolute;
  top: 50%;
  left: 120%;
  border-bottom: 1px solid white;
  animation: 3s ease-out 0s 1 slideInFromRight;
}
.palette{
  color: #E2A9E5; /*.arrowButton*/
  color: #632C65; /*social media icons*/
  color: #4B384C; /*footer*/
}

/*button stuff*/

.arrowButton{
  z-index: 3;
  grid-area: s;
  display: block;
  position: relative;
  width: 100%;
  height: 100%;
  border: none;
  box-shadow: 5px 5px 10px rgba(0,0,0,.1), 
              5px 5px 25px rgba(0,0,0,.1);
}
.arrowButton button{
  display: block;
  width: 100%;
  height: 50%;
  background: #E2A9E5;
  border: none;
  color: white;
}

div.arrowUpIcon, div.arrowDownIcon{
	width: 1.5vmin;
	height: 1.5vmin;
	box-sizing: border-box;
  border-width: 1px 0 0 1px;
  border-style: solid;
  border-color: white;
	position: absolute;
	left: 50%;
	top: 25%;
  transform-origin: 0 0;
	transform: rotate(45deg);
}
div.arrowDownIcon{
  top: 75%;
  transform: rotate(225deg);
}
.arrowUpIcon::before {
  content: '';
}
.arrowDownIcon:after {
  content: '';
}
button:focus{
  outline: none;
}
/*top right menu button*/
.searchDiv{
  position: relative;
  display: flex;
  grid-area: d;
  align-items: center;
  justify-content: center;
}
.searchDiv img{
  display: block;
  box-sizing: content-box;
  width: 20px;
  object-fit: cover;
}
.searchDiv:after{
  display: block;
  position: absolute;
  height: 100%;
  width: 30%;
  content: "";
  border-bottom: 1px solid #E2A9E5;
/*   padding: 0 12%; */
  animation: 3s ease-out fadeIn;
}
.menuButton{
  position: relative;
  border: none !important;
  width: 16px;
  height: 16px;
  padding: 0;
  margin-left: auto;
  margin-right: 0;
  background: none;
  outline: none;
}
button:hover{
  cursor: pointer;
}
.menu-icon {
  width: 16px;
  height: 1px;
  border: none !important;
  background: black;
  position: relative;
  transform: translateY(-700%);
  transition: opacity .2s ease-in-out;
  }
.menu-icon::after {
    content: '';
    width: 6px;
    height: 1px;
    background: black;
    position: absolute;
    top: 10px;
    left: 0px;
  } 
.menu-icon::before{
    content: '';
    width: 10px;
    height: 1px;
    background: black;
    position: absolute;
    top: 5px; 
    left: 0;
}

/* Animation Stuff */
*:not(a){
  animation: 3s ease-out 0s 1 fadeIn;
}
@keyframes slideInFromLeft{
  0%{
    transform: translateX(-130%);
  }
  100%{
    transform: translateX(0);
  }
}
@keyframes slideInFromRight{
  0%{
    transform: translateX(300%);
  }
  100%{
    transform: translateX(0);
  }
}
@keyframes slideInFromBottomActive{
  0%{
    padding-bottom: 300px;
  }
  100%{
  }
}
@keyframes slideInFromTop{
  0%{
    opacity: 0;
    margin-bottom: -100%;;
  }
  100%{
    opacity: 1;
  }
}
@keyframes fadeIn{
  0% {
    opacity: 0;
  }
  100% {
    opacity: 1;
  }
}
</style>




<!-- you can scroll through the grid content  -->
<link href="https://fonts.googleapis.com/css?family=Roboto:900|Sarabun:200,400,800" rel="stylesheet">

<div class="blur"></div>
<nav class="barStyle topBar">
  <span>Prisma</span><strong>Studio</strong>
  <button class="menuButton">
    <div class="menu-icon"></div>
  </button>
</nav>
<div class="container">
  <div class="horizontal">
<!--     <div class="card"></div> -->
    <img src="https://images.unsplash.com/photo-1506252374453-ef5237291d83?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=968&q=80" alt="1">
  </div>
  <div class="big"><img src="https://images.unsplash.com/photo-1486239154832-a989a601c41d?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=634&q=80" alt="4"></div>
  <div class="horizontal"><img src="https://images.unsplash.com/photo-1524546101258-e99b0826ea9c?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1936&q=80" alt="3"></div>
  <div class="vertical"><img src="https://images.unsplash.com/photo-1536273176101-b3810e5cb3c0?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=2000&q=80" alt="2"></div>
  <div class="vertical"><img src="https://images.unsplash.com/photo-1519458524098-335b2a5747b2?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=634&q=80" alt="5"></div>
  <div><img src="https://images.unsplash.com/photo-1548737787-a776beb45cfd?ixlib=rb-1.2.1&auto=format&fit=crop&w=701&q=80" alt="6"></div>
  <div class="horizontal"><img src="https://images.unsplash.com/photo-1543335785-8aadf6d8183c?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1489&q=80" alt="13"></div>
  <div><img src="https://images.unsplash.com/photo-1474218861938-d6b14818c8e2?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1489&q=80" alt="8"></div>
  <div><img src="https://images.unsplash.com/photo-1502842113467-03fdcac39fe5?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=634&q=80" alt="8.1"></div>
  <div><img src="https://images.unsplash.com/photo-1548599042-0df640181771?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1350&q=80" alt="9"></div>
  <div class="big"><img src="https://images.unsplash.com/photo-1515405969538-5642ed9d0cc4?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=675&q=80" alt="10"></div>
  <div class="vertical"><img src="https://images.unsplash.com/photo-1513836279014-a89f7a76ae86?ixlib=rb-1.2.1&auto=format&fit=crop&w=634&q=80" alt="7"></div>
  <div><img src="https://images.unsplash.com/photo-1519999482648-25049ddd37b1?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=926&q=80" alt="11"></div>
  <div class="horizontal"><img src="https://images.unsplash.com/photo-1460627390041-532a28402358?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80" alt="12"></div>
  <div><img src="https://images.unsplash.com/photo-1514673309919-5998bbbadf7f?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=688&q=80" alt="12.1"></div>
</div>
<div class="border leftNavBar centerItem">
  <ul>
    <li><a href="">Home</a></li>
    <li><a class="active" href="">Photos</a></li>
    <li><a href="">About</a></li>
    <li><a href="">Designs</a></li>
  </ul>
</div>
<div class="searchDiv"><img src="https://i.dlpng.com/static/png/255423_preview.png" alt=""></div>
<div class="border aside">
  <a href="https://twitter.com/nuclearfischion"><i class="fab fa-instagram centerItem"></i></a>
  <a href="https://twitter.com/nuclearfischion"><i class="fab fa-facebook-square centerItem"></i></a>
  <a href="https://twitter.com/nuclearfischion"><i class="fab fa-twitter centerItem"></i></a>
  <a href="https://twitter.com/nuclearfischion"><i class="fab fa-google-plus-g centerItem"></i></a>
</div>
<div class="arrowButton">
  <button><div class="arrowUpIcon"></div></button>
  <button onClick="scroll(0, 100);"><div class="arrowDownIcon"></div></button>
</div>
<div class="footer">
  <div class="scroll-icon-wrapper">
      <img class="scroll-icon" src="https://drive.google.com/uc?export=view&id=1NqmpdNb-FhfYNHXwEsKImMRg_PQuGsf4" alt="">
  </div>
</div>
<div class="footer-right">
  <h1>01</h1>
</div>

