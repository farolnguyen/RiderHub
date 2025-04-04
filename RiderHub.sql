-- Tạo cơ sở dữ liệu và sử dụng nó
CREATE DATABASE IF NOT EXISTS RiderHub;
USE RiderHub;
-- Tạo bảng danh mục sản phẩm
CREATE TABLE IF NOT EXISTS category (
id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(100) NOT NULL,
description TEXT,
image VARCHAR(255) DEFAULT NULL
);
-- Tạo bảng danh mục sản phẩm
CREATE TABLE IF NOT EXISTS company (
id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(100) NOT NULL,
description TEXT,
image VARCHAR(255) DEFAULT NULL
);
-- Tạo bảng sản phẩm
CREATE TABLE IF NOT EXISTS product (
id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(100) NOT NULL,
description TEXT,
price DECIMAL(15,2) NOT NULL,
image VARCHAR(255) DEFAULT NULL,
category_id INT,
company_id INT,
FOREIGN KEY (category_id) REFERENCES category(id) ON DELETE CASCADE,
FOREIGN KEY (company_id) REFERENCES company(id) ON DELETE CASCADE
);
-- Chèn dữ liệu vào bảng category
INSERT INTO category (name, DESCRIPTION, image) VALUES
	('Sportbike', 'Dòng xe thể thao với thiết kế khí động học, động cơ mạnh và khả năng tăng tốc nhanh, phù hợp chạy đường trường và đua xe.', 'uploads/categories/Sportbike.jpg'),
   ('Naked Bike', 'Dòng xe tối giản không dàn áo, tư thế lái thoải mái, động cơ mạnh mẽ nhưng dễ kiểm soát, thích hợp cho cả đi phố và đi xa.', 'uploads/categories/Naked Bike.jpg'),
   ('Cruiser', 'Xe có thiết kế thấp, tay lái rộng, phong cách cổ điển, động cơ lớn, phù hợp chạy đường dài với tư thế lái thư giãn.', 'uploads/categories/Cruiser.jpg'),
   ('Chopper', 'Biến thể của Cruiser với tay lái cao, bánh trước dài, thiết kế cá nhân hóa, mang đậm phong cách độ xe.', 'uploads/categories/Chopper.jpg'),
   ('Touring', 'Dòng xe chuyên chạy đường dài, trang bị kính chắn gió, thùng chứa đồ và yên xe êm ái, động cơ mạnh và bền bỉ.', 'uploads/categories/Touring.jpg'),
   ('Dirt Bike', 'Xe địa hình nhẹ, khung gầm cao, lốp gai, hệ thống giảm xóc tốt, phù hợp chạy off-road.', 'uploads/categories/Dirt Bike.jpg'),
   ('Supermoto', 'Kết hợp giữa Dirt Bike và Sportbike, khung gầm cao, lốp trơn chạy phố, linh hoạt và phấn khích khi vào cua.', 'uploads/categories/Supermoto.jpg'),
   ('Café Racer', 'Xe phong cách cổ điển, thiết kế tối giản, bình xăng thuôn dài, yên đơn và tay lái thấp, mang vẻ ngoài cá tính.', 'uploads/categories/Café Racer.jpg'),
   ('Bobber', 'Biến thể của Cruiser, thiết kế tối giản, bánh sau lớn, yên solo, phong cách hoài cổ nhưng mạnh mẽ.', 'uploads/categories/Bobber.jpg'),
   ('Adventure (ADV) and Dual-Sport', 'Dòng xe đa dụng, chạy tốt trên cả đường nhựa và địa hình khó, khung gầm cao, giảm xóc tốt, bình xăng lớn.', 'uploads/categories/Adventure (ADV) and Dual-Sport.jpg');
INSERT INTO company (name, description, image) VALUES
	('Honda', 'Honda Motor Co., Ltd. là một nhà sản xuất xe mô tô và xe ô tô nổi tiếng đến từ Nhật Bản, được biết đến với các dòng xe bền bỉ và hiệu suất cao.', 'uploads/companies/honda_image.jpg'),
	('Kawasaki', 'Kawasaki Heavy Industries Motorcycle & Engine Company, một công ty con của Kawasaki, nổi tiếng với các dòng mô tô phân khối lớn mạnh mẽ và thiết kế độc đáo.', 'uploads/companies/kawasaki_image.jpg'),
	('Yamaha', 'Yamaha Motor Co., Ltd. là một trong những hãng xe mô tô lớn nhất thế giới, với các dòng xe đa dạng và độ bền cao.', 'uploads/companies/yamaha_image.jpg'),
	('Harley-Davidson', 'Harley-Davidson là một thương hiệu nổi tiếng của Mỹ, chuyên sản xuất xe mô tô phân khối lớn với phong cách đặc trưng và lịch sử lâu dài.', 'uploads/companies/harley_davidson_image.jpg'),
	('BMW', 'BMW Motorrad, thuộc Tập đoàn BMW, sản xuất các dòng mô tô phân khối lớn cao cấp, nổi bật với công nghệ tiên tiến và hiệu suất vượt trội.', 'uploads/companies/bmw_image.jpg'),
	('Suzuki', 'Suzuki là một hãng xe mô tô nổi tiếng đến từ Nhật Bản, chuyên sản xuất các dòng mô tô mạnh mẽ với giá cả hợp lý.', 'uploads/companies/suzuki_image.jpg'),
	('Ducati', 'Ducati là một hãng xe mô tô đến từ Ý, nổi bật với thiết kế sang trọng và các dòng xe đua chất lượng cao.', 'uploads/companies/ducati_image.jpg'),
	('Triumph', 'Triumph là một hãng xe mô tô của Anh, nổi tiếng với các dòng xe cổ điển và phong cách mạnh mẽ.', 'uploads/companies/triumph_image.jpg'),
	('Indian Motorcycle', 'Indian Motorcycle là một thương hiệu xe mô tô nổi tiếng đến từ Mỹ, được biết đến với các dòng xe cruiser và touring cao cấp.', 'uploads/companies/indian_motorcycle_image.jpg'),
	('Custom', 'Custom là một loại xe mô tô được chế tạo tùy chỉnh, không thuộc các hãng lớn mà do người sử dụng tự chế tạo.', 'uploads/companies/custom_image.jpg'),
	('KTM', 'KTM là một hãng xe mô tô nổi tiếng đến từ Áo, chuyên sản xuất xe mô tô thể thao và off-road mạnh mẽ.', 'uploads/companies/ktm_image.jpg'),
	('Husqvarna', 'Husqvarna là một thương hiệu nổi tiếng của Thụy Điển, chuyên sản xuất xe mô tô phân khối lớn và xe off-road chất lượng cao.', 'uploads/companies/husqvarna_image.jpg');


-- Chèn dữ liệu vào bảng product

-- Sportbike
INSERT INTO product (name, description, price, category_id, company_id, image) VALUES
('Yamaha YZF-R1', 'Xe thể thao hiệu suất cao với động cơ 998cc.', 500000000, 1,3, 'uploads/bikes/Yamaha YZF-R1.jpg'),
('Honda CBR1000RR', 'Mẫu sportbike nổi tiếng với thiết kế khí động học.', 550000000, 1,1,'uploads/bikes/Honda CBR1000RR.jpg');

-- Naked Bike
INSERT INTO product (name, description, price, category_id, company_id, image) VALUES
('Kawasaki Z900', 'Xe naked bike với động cơ 948cc mạnh mẽ.', 300000000, 2,2, 'uploads/bikes/Kawasaki Z900.jpg'),
('Yamaha MT-09', 'Mẫu naked bike linh hoạt với động cơ 847cc.', 320000000, 2,3, 'uploads/bikes/Yamaha MT-09.jpg');

-- Cruiser
INSERT INTO product (name, description, price, category_id, company_id, image) VALUES
('Harley-Davidson Iron 883', 'Cruiser phong cách cổ điển với động cơ 883cc.', 700000000, 3,4, 'uploads/bikes/Harley-Davidson Iron 883.jpg'),
('Indian Scout', 'Xe cruiser với thiết kế thấp và động cơ 1133cc.', 750000000, 3,9, 'uploads/bikes/Indian Scout.jpg');

-- Chopper
INSERT INTO product (name, description, price, category_id, company_id, image) VALUES
('Custom Chopper One', 'Mẫu chopper với bánh trước dài và thiết kế độc đáo.', 800000000, 4,10, 'uploads/bikes/Custom Chopper One.jpg'),
('Custom Chopper Two', 'Xe chopper tùy chỉnh với tay lái cao.', 850000000, 4,10, 'uploads/bikes/Custom Chopper Two.jpg');

-- Touring
INSERT INTO product (name, description, price, category_id, company_id, image) VALUES
('BMW R1250RT', 'Xe touring cao cấp với trang bị hiện đại.', 900000000, 5,5, 'uploads/bikes/BMW R1250RT.jpg'),
('Honda Gold Wing', 'Mẫu touring huyền thoại với động cơ 1833cc.', 950000000, 5,1, 'uploads/bikes/Honda Gold Wing.jpg');

-- Dirt Bike
INSERT INTO product (name, description, price, category_id, company_id, image) VALUES
('KTM 450 SX-F', 'Xe địa hình nhẹ với khung gầm cao.', 400000000, 6,11, 'uploads/bikes/KTM 450 SX-F.jpg'),
('Yamaha YZ450F', 'Mẫu dirt bike với hệ thống giảm xóc tiên tiến.', 420000000, 6,3, 'uploads/bikes/Yamaha YZ450F.jpg');

-- Supermoto
INSERT INTO product (name, description, price, category_id, company_id, image) VALUES
('Suzuki DR-Z400SM', 'Xe supermoto linh hoạt với động cơ 398cc.', 350000000, 7,6, 'uploads/bikes/Suzuki DR-Z400SM.jpg'),
('Husqvarna 701 Supermoto', 'Mẫu supermoto hiệu suất cao với thiết kế hiện đại.', 370000000, 7,12, 'uploads/bikes/Husqvarna 701 Supermoto.jpg');

-- Café Racer
INSERT INTO product (name, description, price, category_id, company_id, image) VALUES
('Triumph Thruxton RS', 'Xe café racer cổ điển với động cơ 1200cc.', 800000000, 8,8, 'uploads/bikes/Triumph Thruxton RS.jpg'),
('BMW R nineT Racer', 'Mẫu café racer với thiết kế tối giản và hiệu suất cao.', 820000000, 8,5, 'uploads/bikes/BMW R nineT Racer.jpg');

-- Bobber
INSERT INTO product (name, description, price, category_id, company_id, image) VALUES
('Indian Bobber Sixty', 'Xe bobber với thiết kế yên đơn và phong cách hoài cổ.', 700000000, 9,9, 'uploads/bikes/Indian Bobber Sixty.jpg'),
('Triumph Bonneville Bobber', 'Mẫu bobber với bánh sau lớn và động cơ 1200cc.', 720000000, 9,8, 'uploads/bikes/Triumph Bonneville Bobber.jpg');

-- Adventure (ADV) / Dual-Sport
INSERT INTO product (name, description, price, category_id, company_id, image) VALUES
('KTM 1290 Super Adventure R', 'Xe adventure đa dụng với khung gầm cao.', 950000000, 10,11, 'uploads/bikes/KTM 1290 Super Adventure R.jpg'),
('Honda CRF1100L Africa Twin', 'Mẫu dual-sport với khả năng off-road vượt trội.', 980000000, 10,1, 'uploads/bikes/Honda CRF1100L Africa Twin.jpg');
   
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,  -- Tên khách hàng
    phone VARCHAR(15) NOT NULL,  -- Số điện thoại khách hàng
    address TEXT NOT NULL,        -- Địa chỉ giao hàng
    total_price DECIMAL(15,2) NOT NULL DEFAULT 0.00, -- Tổng giá trị đơn hàng
    status ENUM('pending', 'processing', 'shipped', 'completed', 'cancelled') NOT NULL DEFAULT 'pending', -- Trạng thái đơn hàng
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Thời gian tạo đơn
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Thời gian cập nhật
    deleted_at TIMESTAMP NULL DEFAULT NULL  -- Xoá mềm nếu cần
);

CREATE TABLE IF NOT EXISTS order_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,  -- ID đơn hàng
    product_id INT NOT NULL, -- ID sản phẩm
    quantity INT NOT NULL CHECK (quantity > 0), -- Số lượng sản phẩm
    unit_price DECIMAL(15,2) NOT NULL, -- Giá tại thời điểm mua
    subtotal DECIMAL(15,2) GENERATED ALWAYS AS (quantity * unit_price) STORED, -- Tổng giá từng sản phẩm
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Thời gian tạo chi tiết đơn hàng
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Thời gian cập nhật
    deleted_at TIMESTAMP NULL DEFAULT NULL,  -- Xoá mềm nếu cần
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES product(id) ON DELETE CASCADE
);
CREATE TABLE account (
id INT AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(255) NOT NULL UNIQUE,
fullname VARCHAR(255) NOT NULL,
password VARCHAR(255) NOT NULL,
role ENUM('admin', 'user') DEFAULT 'user'
);
