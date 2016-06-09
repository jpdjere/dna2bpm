// Full list of configuration options available at:
			// https://github.com/hakimel/reveal.js#configuration
			Reveal.initialize({
				controls: true,
				progress: true,
				history: true,
				center: true,

				transition: 'slide', // none/fade/slide/convex/concave/zoom

				// Optional reveal.js plugins
				dependencies: [
					{ src: 'assets/jscript/reveal.js/lib/js/classList.js', condition: function() { return !document.body.classList; } },
					{ src: 'assets/jscript/reveal.js/plugin/markdown/marked.js', condition: function() { return !!document.querySelector( '[data-markdown]' ); } },
					{ src: 'assets/jscript/reveal.js/plugin/markdown/markdown.js', condition: function() { return !!document.querySelector( '[data-markdown]' ); } },
					{ src: 'assets/jscript/reveal.js/plugin/highlight/highlight.js', async: true, callback: function() { hljs.initHighlightingOnLoad(); } },
					{ src: 'assets/jscript/reveal.js/plugin/zoom-js/zoom.js', async: true },
					{ src: 'assets/jscript/reveal.js/plugin/notes/notes.js', async: true }
				]
			});