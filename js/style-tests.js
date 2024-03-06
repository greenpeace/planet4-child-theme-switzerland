// Add test controls overlay
const testWindowsElement = document.createElement('div');
testWindowsElement.classList.add('style-test-window');
testWindowsElement.setAttribute('draggable', 1);
testWindowsElement.setAttribute('id', 'testWindowElement');
document.body.appendChild(testWindowsElement);

const experimentSettings = '<ul>' +
    '<li>Experiment 1: <select name="exp1" data-index="0"><option value="A">A</option><option value="B">B</option></select> (Header fonts)</li>' +
    '<li>Experiment 2: <select name="exp2" data-index="1"><option value="A">A</option><option value="B">B</option></select> (Text fonts)</li>' +
    '<!--<li>Experiment 3: <select name="exp3" data-index="2"><option value="A">A</option><option value="B">B</option></select> (Color schemes)</li>' +
    '<li>Experiment 4: <select name="exp4" data-index="3"><option value="A">A</option><option value="B">B</option></select></li>' +
    '--></ul>';

testWindowsElement.innerHTML = '<div class="icons"><span class="icon expand-icon">⤢</span><span id="testWindowElementDrag" class="icon drag-icon">✥</span></div><div class="settings">' + experimentSettings + '</div>';


addEventListener("DOMContentLoaded", (event) => {
    let gpchStyleTest = localStorage.getItem("gpchStyleTest");

    const updateBodyClasses = () => {
        // Remove all class names
        const prefix = "p4gpch-exp";
        const classes = document.body.className.split(" ").filter(c => !c.startsWith(prefix));
        document.body.className = classes.join(" ").trim();

        // Add current class names
        let i = 0;
        gpchStyleTest.settings.forEach((value) => {
            document.body.classList.add(prefix + i + "-" + value);
            i++;
        });
    }

    if (gpchStyleTest === null) {
        // Set local storage when query string is present
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(window.location.search);
        const styleTestParam = urlParams.get('styleTest');

        if (styleTestParam === "1") {
            // Initialize storage
            gpchStyleTest = {
                settings: ["A", "A", "B", "A"]
            };
            localStorage.setItem("gpchStyleTest", JSON.stringify(gpchStyleTest));
        }
    }

    if (typeof gpchStyleTest === "string") {
        gpchStyleTest = JSON.parse(gpchStyleTest);
    }

    if (typeof gpchStyleTest === "object" && gpchStyleTest !== null) {
        testWindowsElement.style.display = "block";

        // Initialize settings fields from storage
        const settingsFields = document.querySelectorAll("#testWindowElement .settings select");

        let i = 0;
        settingsFields.forEach((element) => {
            element.value = gpchStyleTest.settings[i]
            i++;
        })

        updateBodyClasses();

        // Watch setting changes
        settingsFields.forEach((element) => {
            element.onchange = (event) => {
                // Update settings in storage
                gpchStyleTest.settings[event.target.dataset.index] = event.target.value;
                localStorage.setItem("gpchStyleTest", JSON.stringify(gpchStyleTest));

                updateBodyClasses();
            }
        });
    }


    // Open/close Icon
    const openCloseIcon = document.querySelector("#testWindowElement .expand-icon");
    const settingsPanel = document.querySelector("#testWindowElement .settings")
    openCloseIcon.addEventListener("click", (event) => {
        if (settingsPanel.style.display === "block") {
            settingsPanel.style.display = "none";
        } else {
            settingsPanel.style.display = "block";
        }
    });

    // Dragging functionality
    dragElement(document.getElementById('testWindowElement'));

    function dragElement(elmnt) {
        var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
        if (document.getElementById(elmnt.id + "Drag")) {
            // if present, the header is where you move the DIV from:
            document.getElementById(elmnt.id + "Drag").onmousedown = dragMouseDown;
        } else {
            // otherwise, move the DIV from anywhere inside the DIV:
            elmnt.onmousedown = dragMouseDown;
        }

        function dragMouseDown(e) {
            e = e || window.event;
            e.preventDefault();
            // get the mouse cursor position at startup:
            pos3 = e.clientX;
            pos4 = e.clientY;
            document.onmouseup = closeDragElement;
            // call a function whenever the cursor moves:
            document.onmousemove = elementDrag;
        }

        function elementDrag(e) {
            e = e || window.event;
            e.preventDefault();
            // calculate the new cursor position:
            pos1 = pos3 - e.clientX;
            pos2 = pos4 - e.clientY;
            pos3 = e.clientX;
            pos4 = e.clientY;
            // set the element's new position:
            elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
            elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
        }

        function closeDragElement() {
            // stop moving when mouse button is released:
            document.onmouseup = null;
            document.onmousemove = null;
        }
    }
});
