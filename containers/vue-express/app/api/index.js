/**
 * /api/
 */
const express = require('express');
const router = express.Router();

// GET / == /api/ => {message: 'Hello, Express!'}
router.get('/', (req, res) => {
  res.json({
    message: 'Hello, Express!'
  });
})

// export
module.exports = router;
