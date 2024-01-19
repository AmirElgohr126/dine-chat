const express = require('express');
const http = require('http');
const socketIO = require('socket.io');
const jwt = require('jsonwebtoken');
const SECRET_KEY = 'Vv6QXD2aogq7FMgynetIl96Xh4R6IrgDWtiqc6eHgRchNRUlv843TX0gz7B6ndSL';
// ==============================================
// Set up Express and Socket.IO
const app = express();
const server = http.createServer(app);
const io = socketIO(server);
// ==============================================
// Body parser middleware to handle POST requests
app.use(express.json());

app.post('/broadcast', (request, response) => {
    const token = request.headers.authorization.split(' ')[1];
    try {
        const decoded = jwt.verify(token, SECRET_KEY);
        const { event, data, channel } = request.body;
        io.to(channel).emit(event, data);
        response.send({ status: 'Event broadcasted' });
    } catch (error) {
        response.status(401).send({ status: 'Unauthorized' });
    }
});

// Handle new WebSocket connections
io.on('connection', (socket) => {
    console.log('A user connected');

    // Handling channel subscription
    socket.on('join-channel', (channel, token) => {
        try {
            jwt.verify(token, SECRET_KEY);
            socket.join(channel);
        } catch (error) {
            console.log('Failed to join channel: Unauthorized');
        }
    });

    // Handle disconnection
    socket.on('disconnect', () => {
        console.log('A user disconnected');
    });
});

// Start the server
const PORT = 3000;
server.listen(PORT, () => {
    console.log(`Server running on port ${PORT}`);
});
