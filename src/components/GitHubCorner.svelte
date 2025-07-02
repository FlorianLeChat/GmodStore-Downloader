<a
	rel="noopener noreferrer"
	href="https://github.com/FlorianLeChat/GmodStore-Downloader"
	title="GitHub"
	target="_blank"
	aria-label="GitHub"
>
	<svg width="80" height="80" viewBox="0 0 250 250">
		<path d="M0 0l115 115h15l12 27 108 108V0z" />
		<path
			d="M128 109c-15-9-9-19-9-19 3-7 2-11 2-11-1-7 3-2 3-2 4 5 2 11 2 11-3 10 5 15 9 16"
		/>
		<path
			d="M115 115s4 2 5 0l14-14c3-2 6-3 8-3-8-11-15-24 2-41 5-5 10-7 16-7 1-2 3-7 12-11 0 0
			5 3 7 16 4 2 8 5 12 9s7 8 9 12c14 3 17 7 17 7-4 8-9 11-11 11 0 6-2 11-7 16-16 16-30 10-41
			2 0 3-1 7-5 11l-12 11c-1 1 1 5 1 5z"
		/>
	</svg>
</a>

<style>
	/* Lien vers le dépôt GitHub */
	a {
		inset: 0 0 auto auto;
		position: fixed;
		clip-path: polygon(0 0, 100% 0, 100% 100%);
	}

	@media screen and (max-width: 1024px) {
		a {
			display: none;
		}
	}

	/* Animation du logo GitHub */
	a:hover path:nth-of-type(2) {
		animation: octocat-wave 560ms ease-in-out;
	}

	/* Délimitation de la zone cliquable */
	svg {
		fill: #151513;
		color: white;
	}

	svg path:nth-of-type(2) {
		/* Bras du logo GitHub */
		fill: currentColor;
		transform-origin: 130px 106px;
	}

	svg path:nth-of-type(3) {
		/* Corps du logo GitHub */
		fill: currentColor;
	}

	@keyframes octocat-wave {
		0%,
		100% {
			transform: rotate(0);
		}
		20%,
		60% {
			transform: rotate(-25deg);
		}
		40%,
		80% {
			transform: rotate(10deg);
		}
	}
</style>