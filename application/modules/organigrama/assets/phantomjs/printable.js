var page = require('webpage').create(),
    system = require('system'),
    address, output, width, height, size;


if (system.args.length != 3) {
    console.log('Usage: printable.js URL filename');
}
else {
    address = system.args[1];
    output = system.args[2];
    console.log(address);
    console.log(output);

    page.viewportSize = {
        width: 800,
        height: 600
    };
    page.open(address, function(status) {

        if (status !== 'success') {
            console.log('Unable to load the address!');
            phantom.exit(1);
        }
        else {
            window.setTimeout(function() {
                try {
                    size = page.evaluate(function() {
                        return document.querySelector('#orgdiagram svg').getBoundingClientRect();
                    });
                    // console.log('size', size.width, size.height);


                }
                catch (e) {
                    console.log("Unable to fetch bounds for element '#orgdiagram svg' warning", e);
                }
                render(size.width, size.height);
            }, 300);
        }
    });

    function render(width, height) {
        page.zoomFactor=2;
        page.viewportSize = {
            width: 2*parseInt(size.width),
            height:2*parseInt(size.height)
        };
        page.open(address, function(status) {

            if (status !== 'success') {
                console.log('Unable to load the address!');
                phantom.exit(1);
            }
            else {
                window.setTimeout(function() {
                    page.render(output);
                    phantom.exit();

                }, 300);
            }
        });
    }
}