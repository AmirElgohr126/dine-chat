<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}
#container {
    display: flex;
    flex-direction: column;
    height: 100vh;
    justify-content: space-between;
}
body {
    font-family: 'Arial', sans-serif;
}

#sidebar {
    width: 200px;
    background: linear-gradient(to right, #f0f0f0, #e6e6e6);
    padding: 10px;
    box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.2);
}

#whiteboard {
    width: 80%;
    height: 500px;
    border: 1px solid black;
    margin-left: 210px;
    position: relative;
}

.draggable {
    margin-bottom: 15px;
    padding: 10px;
    background-color: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
    cursor: grab;
    width: 100px;
    height: 100px;
    transition: transform 0.2s;
}

.draggable:hover {
    transform: scale(1.05);
}

.draggable img {
    border-radius: 8px;
    width: 100%;
    height: 100%;
    object-fit: cover;
    pointer-events: none;
}

#sidebar-header {
    font-size: 18px;
    color: #333;
    text-align: center;
    margin-bottom: 20px;
}

    </style>
</head>

<body>
    <div id="container">
    <div id="sidebar">
        <div class="draggable" id="object1" draggable="true">
            <img src="{{ asset('storage/Dafaults/Contacts/avatar.png') }}" alt="Object 1">
        </div>
        <br><br>
        <div class="draggable" id="object2" draggable="true">
            <img src="{{ asset('storage/Dafaults/Contacts/avatar.png') }}" alt="Object 2">
        </div>
    </div>

    <div id="whiteboard">
        </div>
    </div>
    <script>
        function updateElementPosition(element, x, y) {
            setTimeout(function() {
                var adjustedX = x - element.offsetWidth / 2;
                var adjustedY = y - element.offsetHeight / 2;
                element.style.position = 'absolute';
                element.style.left = adjustedX + 'px';
                element.style.top = adjustedY + 'px';
            }, 0);
        }
        function makeDraggable(element, isSidebarElement) {
            element.draggable = true;
            element.addEventListener('dragstart', function(event) {
                var rect = element.getBoundingClientRect();
                var offsetX = event.clientX - (rect.left + rect.width / 2);
                var offsetY = event.clientY - (rect.top + rect.height / 2);
                event.dataTransfer.setData("text", event.target.id);
                event.dataTransfer.setData("isSidebarElement", isSidebarElement);
                event.dataTransfer.setData("offsetX", offsetX);
                event.dataTransfer.setData("offsetY", offsetY);
            });
        }
        var sidebarDraggables = document.querySelectorAll('#sidebar .draggable');
        sidebarDraggables.forEach(elem => makeDraggable(elem, true));
        var whiteboard = document.getElementById('whiteboard');
        whiteboard.addEventListener('dragover', function(event) {
            event.preventDefault();
        });
        whiteboard.addEventListener('drop', function(event) {
            event.preventDefault();
            var data = event.dataTransfer.getData("text");
            var isSidebarElement = event.dataTransfer.getData("isSidebarElement") === 'true';
            var offsetX = parseFloat(event.dataTransfer.getData("offsetX"));
            var offsetY = parseFloat(event.dataTransfer.getData("offsetY"));
            var elementToMove = document.getElementById(data);
            var whiteboardRect = whiteboard.getBoundingClientRect();
            var x = event.clientX - whiteboardRect.left - offsetX;
            var y = event.clientY - whiteboardRect.top - offsetY;
            if (isSidebarElement) {
                var copiedElement = elementToMove.cloneNode(true);
                copiedElement.id = 'copy_' + Math.random().toString(36).substr(2, 9);
                updateElementPosition(copiedElement, x, y);
                makeDraggable(copiedElement, false);
                whiteboard.appendChild(copiedElement);
            } else {
                updateElementPosition(elementToMove, x, y);
            }
        });
    </script>
</body>

</html>
