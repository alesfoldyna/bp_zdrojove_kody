<template>
    <div class="LoadingComp container">
        <div class="card mt-3">
            <div class="ring">
                Loading
                <span></span>
            </div>
        </div>
    </div>
</template>

<script>
/**
 * @vuese
 * Komponenta signalizující načítání, nemá žádnou jinou funkcionalitu.
 */
export default {
  name: 'LoadingComp',
  data () {
    return {

    }
  }
}
</script>

<style scoped>
.card {
    margin:0;
    padding:0;
    font-family:verdana;
    background:#2626260c;
    height: 300px;
}
.ring {
	position: absolute;
	top: 50%;
	left: 50%;
	transform: translate(-50%,-50%); 
	width: 150px;
	height: 150px;
	background: transparent;
	border: 1px solid rgb(0, 0, 0);
	border-radius: 50%;
	text-align: center;
	line-height: 150px;
	font-family: sans-serif;
	font-size: 20px;
	color: #000000;
	letter-spacing:4px;
	text-transform: uppercase;
	text-shadow:0 0 10px #000000;
	box-shadow: 0 0 20px rgba(0,0,0,.5);
}
.ring:before {
	content: '';
	position: absolute;
	top: -3px;
	left: -3px;
	width: 100%;
	height: 100%;
	border: 3px solid transparent;
	border-top: 3px solid #4a5255;
	border-right: 3px solid #4a5255;
	border-radius: 50%;
	animation: animateCircle 2s linear infinite;
}
span {
	display: block;
	position: absolute;
	top: calc(50% - 2px);
	left: 50%;
	width: 50%;
	height: 4px;
	background: transparent;
	transform-origin:left;
	animation: animate 2s linear infinite;
}
span:before {
	content:'';
	position: absolute;
	width: 16px;
	height: 16px;
	border-radius: 50%;
	background-color: #4a5255;
	top: -6px;
	right: -8px;
	box-shadow: 0 0 20px #4a5255;
}

 @keyframes animateCircle
{
	0%
	{
		transform: rotate(0deg);
	}
	100%
	{
		transform: rotate(360deg);
	}
}
@keyframes animate
{
	0%
	{
		transform: rotate(45deg);
	}
	100%
	{
		transform: rotate(405deg);
	}
}
</style>
