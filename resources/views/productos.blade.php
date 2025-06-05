<!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script para el carrito -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const botonesAgregarCarrito = document.querySelectorAll('.agregar-carrito');

            botonesAgregarCarrito.forEach(boton => {
                boton.addEventListener('click', function() {
                    const productoId = this.getAttribute('data-id');


                    fetch('/carrito/agregar', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            producto_id: productoId,
                            cantidad: 1
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Producto agregado al carrito');
                        } else {
                            alert('Error al agregar el producto al carrito');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error al agregar el producto al carrito');
                    });
                });
            });
        });
    </script>
</body>
</html>
