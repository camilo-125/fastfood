-- Datos iniciales para el sistema
USE fastbite_db;

-- Insertar usuario administrador por defecto
-- Contraseña: admin123 (encriptada con password_hash)
INSERT INTO usuarios (nombre, email, contrasena, genero, rol) VALUES
('Administrador', 'admin@fastbite.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'otro', 'administrador'),
('Empleado Demo', 'empleado@fastbite.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'masculino', 'empleado'),
('Cliente Demo', 'cliente@fastbite.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'femenino', 'cliente');

-- Insertar productos de ejemplo
INSERT INTO productos (nombre, descripcion, ingredientes, precio, categoria, imagen) VALUES
('Hamburguesa Clásica', 'Deliciosa hamburguesa con queso', 'Carne, queso, lechuga, tomate, cebolla, pan', 8.99, 'hamburguesas', 'burger-classic.jpg'),
('Hamburguesa Doble', 'Doble carne y doble queso', 'Doble carne, doble queso, lechuga, tomate, pepinillos, pan', 12.99, 'hamburguesas', 'burger-double.jpg'),
('Hamburguesa BBQ', 'Con salsa BBQ y tocino', 'Carne, queso, tocino, cebolla caramelizada, salsa BBQ, pan', 11.99, 'hamburguesas', 'burger-bbq.jpg'),
('Papas Fritas Medianas', 'Crujientes papas fritas', 'Papas, sal', 3.99, 'acompañamientos', 'fries-medium.jpg'),
('Papas Fritas Grandes', 'Porción grande de papas', 'Papas, sal', 5.99, 'acompañamientos', 'fries-large.jpg'),
('Nuggets de Pollo', '10 piezas de nuggets', 'Pollo empanizado', 7.99, 'pollo', 'nuggets.jpg'),
('Alitas Picantes', '8 piezas de alitas', 'Alitas de pollo, salsa picante', 9.99, 'pollo', 'wings.jpg'),
('Refresco Mediano', 'Bebida gaseosa 500ml', 'Refresco', 2.49, 'bebidas', 'soda-medium.jpg'),
('Refresco Grande', 'Bebida gaseosa 750ml', 'Refresco', 3.49, 'bebidas', 'soda-large.jpg'),
('Malteada de Chocolate', 'Cremosa malteada', 'Helado, leche, chocolate', 4.99, 'bebidas', 'shake-chocolate.jpg'),
('Ensalada César', 'Fresca ensalada con pollo', 'Lechuga, pollo, crutones, queso parmesano, aderezo césar', 8.49, 'ensaladas', 'salad-caesar.jpg'),
('Hot Dog Especial', 'Hot dog con todos los ingredientes', 'Salchicha, pan, mostaza, ketchup, cebolla, pepinillos', 5.99, 'otros', 'hotdog.jpg');

-- Insertar pedidos de ejemplo
INSERT INTO pedidos (id_usuario, estado, total, notas) VALUES
(3, 'Pendiente', 21.98, 'Sin cebolla por favor'),
(3, 'En preparación', 15.48, NULL),
(3, 'Listo', 12.99, 'Para llevar'),
(3, 'Entregado', 18.97, NULL);

-- Insertar detalles de pedidos
INSERT INTO detalle_pedidos (id_pedido, id_producto, cantidad, precio_unitario, subtotal) VALUES
(1, 1, 2, 8.99, 17.98),
(1, 4, 1, 3.99, 3.99),
(2, 3, 1, 11.99, 11.99),
(2, 8, 1, 2.49, 2.49),
(3, 2, 1, 12.99, 12.99),
(4, 6, 1, 7.99, 7.99),
(4, 7, 1, 9.99, 9.99);
