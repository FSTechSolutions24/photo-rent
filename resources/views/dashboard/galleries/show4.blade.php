<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>4 Column Image Gallery</title>
  <style>
    /* Reset */
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }
 
    body {
      background: #f5f5f5;
      font-family: Arial, sans-serif;
      padding: 20px;
    }
 
    h1 {
      text-align: center;
      margin-bottom: 20px;
      color: #333;
    }
 
    .gallery {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 10px;
    }
 
    .gallery img {
      width: 100%;
      height: auto;
      border-radius: 8px;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      object-fit: cover; /* keeps scale and aspect ratio */
    }
 
    .gallery img:hover {
      transform: scale(1.05);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }
 
    /* Responsive layout for smaller screens */
    @media (max-width: 1024px) {
      .gallery {
        grid-template-columns: repeat(3, 1fr);
      }
    }
 
    @media (max-width: 768px) {
      .gallery {
        grid-template-columns: repeat(2, 1fr);
      }
    }
 
    @media (max-width: 480px) {
      .gallery {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>
  <h1>🎟️ Random Ticket Gallery</h1>
 
  <div class="gallery">
    <!-- Using Unsplash random ticket-related images -->
    <img src="https://images.unsplash.com/photo-1544568100-847a948585b9?ixlib=rb-1.2.1&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=400&fit=max&ixid=eyJhcHBfaWQiOjE0NTg5fQ" alt="Ticket Image 1">
    <img src="https://images.unsplash.com/photo-1504595403659-9088ce801e29?ixlib=rb-1.2.1&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=400&fit=max&ixid=eyJhcHBfaWQiOjE0NTg5fQ" alt="Ticket Image 2">
    <img src="https://images.unsplash.com/photo-1510771463146-e89e6e86560e?ixlib=rb-1.2.1&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=400&fit=max&ixid=eyJhcHBfaWQiOjE0NTg5fQ" alt="Ticket Image 3">
    <img src="https://images.unsplash.com/photo-1507146426996-ef05306b995a?ixlib=rb-1.2.1&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=400&fit=max&ixid=eyJhcHBfaWQiOjE0NTg5fQ" alt="Ticket Image 4">
    <img src="https://images.unsplash.com/photo-1517423440428-a5a00ad493e8?ixlib=rb-1.2.1&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=400&fit=max&ixid=eyJhcHBfaWQiOjE0NTg5fQ" alt="Ticket Image 5">
    <img src="https://images.unsplash.com/photo-1530281700549-e82e7bf110d6?ixlib=rb-1.2.1&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=400&fit=max&ixid=eyJhcHBfaWQiOjE0NTg5fQ" alt="Ticket Image 6">
    <img src="https://source.unsplash.com/random/806x606?ticket" alt="Ticket Image 7">
    <img src="https://source.unsplash.com/random/807x607?ticket" alt="Ticket Image 8">
  </div>
</body>
</html>