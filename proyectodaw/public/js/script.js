window.onload = function() {
    fetch('api/index.php')
        .then(response => response.json())
        .then(data => {
            let productosContainer = document.getElementById('productos-container');
            data.forEach(producto => {
                let productoDiv = document.createElement('div');
                productoDiv.classList.add('producto');
                productoDiv.innerHTML = `
                    <h3>${producto.nombre}</h3>
                    <p>${producto.descripcion}</p>
                    <p>Precio: $${producto.precio}</p>
                    <p>Stock: ${producto.stock}</p>
                `;
                productosContainer.appendChild(productoDiv);
            });
        })
        .catch(error => console.log('Error:', error));
};
