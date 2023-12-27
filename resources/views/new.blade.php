<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interactive Design Board</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            display: flex;
            height: 100vh;
        }
        #sidebar {
            flex: 0 0 200px;
            display: flex;
            flex-direction: column;
            background: #f0f0f0;
            padding: 10px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }
        #whiteboard {
            flex-grow: 1;
            background: #ffffff;
            margin-left: 10px;
            border: 1px solid #000;
            position: relative;
        }
        .draggable {
            padding: 10px;
            margin: 10px 0;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-shadow: 1px 1px 2px rgba(0, 0, 0, 0.05);
            cursor: grab;
        }
        .draggable:last-child {
            margin-bottom: 0;
        }
        .draggable img {
            width: 100%;
            height: auto;
        }
        .icon {
            width: 50px;
            height: 50px;
            background-color: #ccc;
            border-radius: 4px;
            margin-bottom: 10px;
        }
        .icon-table {
            /* Placeholder styles for the table icon */
        }
        .icon-chair {
            /* Placeholder styles for the chair icon */
        }
        .icon-another-table {
            /* Placeholder styles for the second table icon */
        }
        #elements {
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        #elements-header {
            font-size: 18px;
            color: #333;
            text-align: center;
            margin-bottom: 10px;
        }
        #properties-sidebar {
            flex: 0 0 200px;
            background: #f7f7f7;
            padding: 10px;
            box-shadow: -2px 0 5px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
        }

        .property-item {
            padding: 5px;
            margin: 5px 0;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-shadow: 1px 1px 2px rgba(0, 0, 0, 0.05);
        }

        #properties-header {
            font-size: 18px;
            color: #333;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <aside id="sidebar">
        <div id="elements-header">Elements</div>
        <div class="icon icon-table draggable" draggable="true"></div>
        <div class="icon icon-chair draggable" draggable="true"></div>
        <div class="icon icon-another-table draggable" draggable="true"></div>
    </aside>
    <section id="whiteboard">
        <!-- Draggable elements will be placed here -->
    </section>

     <aside id="properties-sidebar">
        <div id="properties-header">Properties</div>
        <!-- Properties will be listed here -->
    </aside>
    <!-- JavaScript code for drag and drop functionality can be included here -->
    <script>
        
    </script>
</body>
</html>
