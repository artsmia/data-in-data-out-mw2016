watch:
	markdown-to-slides -d -w Readme.md -o slides.html

hot-reload:
	rewatch slides.html -c "canary-cli reload"

install:
	npm i -g markdown-to-slides rewatch
