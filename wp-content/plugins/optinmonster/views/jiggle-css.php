<style type="text/css">
	.om-notifications-count:not(.count-0) {
		animation-duration: 10s;
		animation-timing-function: ease-out;
		animation-delay: 3s;
		animation-iteration-count: infinite;
		animation-direction: normal;
		animation-fill-mode: both;
		animation-play-state: running;
		animation-name: omjiggle;
	}
	@keyframes omjiggle {
		<?php // these percentages cause the animation to occur for 1s, followed by 9s pause ?>
		1%, 9% {
			transform: translate3d(-1px, 0, 0);
		}

		2%, 8% {
			transform: translate3d(2px, 0, 0);
		}

		3%, 5%, 7% {
			transform: translate3d(-3px, 0, 0);
		}

		4%, 6% {
			transform: translate3d(3px, 0, 0);
		}
	}
</style>
