<?php
// Bạn có thể thêm logic PHP xử lý dữ liệu ở đây nếu cần trong tương lai
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>C&J Shop - Hệ Thống Đặt Món</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- React & ReactDOM -->
    <script src="https://unpkg.com/react@18/umd/react.production.min.js" crossorigin></script>
    <script src="https://unpkg.com/react-dom@18/umd/react-dom.production.min.js" crossorigin></script>

    <!-- Babel -->
    <script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        body.modal-open {
            overflow: hidden;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes zoomIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translate(-50%, 20px);
            }

            to {
                opacity: 1;
                transform: translate(-50%, 0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.2s ease-out forwards;
        }

        .animate-zoom-in {
            animation: zoomIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .animate-slide-up {
            animation: slideUp 0.3s ease-out forwards;
        }
    </style>
</head>

<body class="bg-[#F8FAFC] text-slate-800 font-sans selection:bg-orange-200">

    <div id="root"></div>

    <script type="text/babel">
        const { useState, useEffect, useMemo } = React;

        function App() {
            // --- STATE ---
            const [lang, setLang] = useState('vi');
            const [activeFilter, setActiveFilter] = useState('All');
            const [isLangMenuOpen, setIsLangMenuOpen] = useState(false);
            const [selectedProduct, setSelectedProduct] = useState(null);
            const [qty, setQty] = useState(1);
            const [size, setSize] = useState('M');
            const [toastMsg, setToastMsg] = useState('');

            useEffect(() => {
                if (selectedProduct) document.body.classList.add('modal-open');
                else document.body.classList.remove('modal-open');
            }, [selectedProduct]);

            // --- DICTIONARY ---
            const dict = {
                vi: {
                    brand: 'C&J Shop', home: 'Trang chủ', category: 'Danh mục', about: 'Về chúng tôi', search: 'Tìm đồ uống...',
                    heroBadge: 'Ưu đãi mùa hè', heroTitle: 'Thanh Mát Ngày Hè', heroDesc: 'Đánh bay cơn nóng với bộ sưu tập nước ép tươi và cà phê ủ lạnh mới nhất. Giảm 20% cho đơn hàng đầu tiên!',
                    orderNow: 'Đặt Hàng Ngay', featured: 'Sản Phẩm Nổi Bật', viewAll: 'Xem tất cả', addToCart: 'Thêm vào giỏ',
                    sizeTitle: 'Kích cỡ', sizeM: 'Size M', sizeL: 'Size L', iceTitle: 'Lượng đá', sugarTitle: 'Lượng đường',
                    normal: 'Bình thường', less: 'Ít', none: 'Không', more: 'Nhiều', dummyMsg: 'Sản phẩm đã được thêm vào giỏ hàng!',
                    filters: [
                        { id: 'All', label: 'Tất cả' },
                        { id: 'Coffee', label: 'Cà phê' },
                        { id: 'Juice', label: 'Nước ép' },
                        { id: 'Fruit Tea', label: 'Trà trái cây' }
                    ]
                },
                en: {
                    brand: 'C&J Shop', home: 'Home', category: 'Categories', about: 'About Us', search: 'Search drinks...',
                    heroBadge: 'Summer Promo', heroTitle: 'Summer Refresh', heroDesc: 'Beat the heat with our latest collection of fresh juices and cold brew coffee. 20% off your first order!',
                    orderNow: 'Order Now', featured: 'Featured Products', viewAll: 'View all', addToCart: 'Add to Cart',
                    sizeTitle: 'Size', sizeM: 'Size M', sizeL: 'Size L', iceTitle: 'Ice Level', sugarTitle: 'Sugar Level',
                    normal: 'Normal', less: 'Less', none: 'None', more: 'More', dummyMsg: 'Product added to cart successfully!',
                    filters: [
                        { id: 'All', label: 'All' },
                        { id: 'Coffee', label: 'Coffee' },
                        { id: 'Juice', label: 'Juice' },
                        { id: 'Fruit Tea', label: 'Fruit Tea' }
                    ]
                }
            };
            const t = dict[lang];

            // --- PRODUCT DATA ---
            const products = [
                { 
                    id: 1, 
                    category: 'Coffee', 
                    name_vi: 'Cà Phê Sữa Đá', 
                    name_en: 'Iced Milk Coffee', 
                    price: 35000, 
                    img: 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&q=80&w=800', 
                    desc_vi: 'Sự kết hợp hoàn hảo giữa cà phê Robusta đậm đà và sữa đặc béo ngậy, phong cách truyền thống Việt Nam.', 
                    desc_en: 'A perfect blend of bold Robusta coffee and creamy condensed milk, classic Vietnamese style.' 
                },
                { 
                    id: 2, 
                    category: 'Fruit Tea', 
                    name_vi: 'Trà Đào Cam Sả', 
                    name_en: 'Peach Lemongrass Tea', 
                    price: 50000, 
                    img: 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?auto=format&fit=crop&q=80&w=800', 
                    desc_vi: 'Thức uống giải nhiệt đỉnh cao với vị trà đen đậm đà, hương đào thơm ngọt hòa quyện cùng cam tươi và sả.', 
                    desc_en: 'Ultimate refreshing drink with bold black tea, sweet peach aroma, fresh orange and lemongrass.' 
                },
                { 
                    id: 3, 
                    category: 'Juice', 
                    name_vi: 'Nước Ép Cam Tươi', 
                    name_en: 'Fresh Orange Juice', 
                    price: 45000, 
                    img: 'https://images.unsplash.com/photo-1600271886742-f049cd451bba?auto=format&fit=crop&q=80&w=800', 
                    desc_vi: '100% cam tươi vắt nguyên chất, không đường hóa học, cung cấp nguồn Vitamin C dồi dào mỗi ngày.', 
                    desc_en: '100% freshly squeezed orange juice, no artificial sweeteners, rich in daily Vitamin C.' 
                },
                { 
                    id: 4, 
                    category: 'Coffee', 
                    name_vi: 'Bạc Xỉu Đá', 
                    name_en: 'White Coffee', 
                    price: 39000, 
                    img: 'https://images.unsplash.com/photo-1572442388796-11668a67e53d?auto=format&fit=crop&q=80&w=800', 
                    desc_vi: 'Dành cho những người yêu thích vị ngọt ngào của sữa nhiều hơn cà phê, thơm béo và dễ uống.', 
                    desc_en: 'For those who love sweet milky taste more than strong coffee, creamy and easy to drink.' 
                },
                { 
                    id: 5, 
                    category: 'Coffee', 
                    name_vi: 'Cà Phê Muối', 
                    name_en: 'Salted Cream Coffee', 
                    price: 42000, 
                    img: 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?auto=format&fit=crop&q=80&w=800', 
                    desc_vi: 'Hot trend với lớp kem mặn béo ngậy bên trên lớp cà phê phin đậm đà, tạo nên hương vị độc đáo.', 
                    desc_en: 'Hot trend featuring rich salted cream foam over traditional filter coffee for a unique taste.' 
                },
                { 
                    id: 6, 
                    category: 'Juice', 
                    name_vi: 'Nước Ép Dưa Hấu', 
                    name_en: 'Watermelon Juice', 
                    price: 40000, 
                    img: 'https://st.quantrimang.com/photos/image/2020/06/22/cach-lam-nuoc-ep-dua-hau-3.jpg', 
                    desc_vi: 'Mát lạnh và ngọt thanh từ dưa hấu đỏ tươi, giúp giải nhiệt tức thì trong những ngày nắng nóng.', 
                    desc_en: 'Cool and naturally sweet from fresh red watermelons, provides instant cooling on hot days.' 
                },
                { 
                    id: 7, 
                    category: 'Fruit Tea', 
                    name_vi: 'Trà Vải Hoa Hồng', 
                    name_en: 'Lychee Rose Tea', 
                    price: 52000, 
                    img: 'https://images.unsplash.com/photo-1597318181409-cf64d0b5d8a2?auto=format&fit=crop&q=80&w=800', 
                    desc_vi: 'Sự kết hợp tinh tế giữa hương thơm hoa hồng quyến rũ và vị ngọt lịm của những trái vải mọng nước.', 
                    desc_en: 'Exquisite combination of charming rose fragrance and the sweet taste of juicy lychees.' 
                },
                { 
                    id: 8, 
                    category: 'Juice', 
                    name_vi: 'Sinh Tố Bơ Xay', 
                    name_en: 'Avocado Smoothie', 
                    price: 55000, 
                    img: 'https://agriculturevn.com/wp-content/uploads/2020/03/15074249312298_sinh-to-bo-tao-kiwi-1.jpg', 
                    desc_vi: 'Bơ sáp loại một được xay mịn cùng sữa đặc, béo ngậy và cực kỳ bổ dưỡng.', 
                    desc_en: 'Premium avocado blended smoothly with condensed milk, creamy and extremely nutritious.' 
                }
            ];
                
            
            // --- FILTER LOGIC ---
            const filteredProducts = useMemo(() => {
                if (activeFilter === 'All') return products;
                return products.filter(p => p.category === activeFilter);
            }, [activeFilter, products]);

            // --- UTILS ---
            const formatPrice = (price) => lang === 'vi' ? new Intl.NumberFormat('vi-VN').format(price) + ' ₫' : new Intl.NumberFormat('en-US').format(price) + ' VND';
            
            const handleAddToCart = () => {
                setToastMsg(t.dummyMsg); setSelectedProduct(null);
                setTimeout(() => setToastMsg(''), 3000);
            };

            return (
                <div className="min-h-screen flex flex-col">
                    {/* HEADER */}
                    <header className="fixed top-0 left-0 right-0 bg-white/90 backdrop-blur-md shadow-sm border-b border-slate-100 z-40">
                        <div className="max-w-7xl mx-auto px-4 sm:px-6 h-20 flex items-center justify-between">
                            <div className="flex items-center gap-3 cursor-pointer group">
                                <div className="bg-gradient-to-br from-orange-400 to-orange-600 p-2.5 rounded-2xl shadow-lg">
                                    <i className="fa-solid fa-mug-hot text-white text-xl"></i>
                                </div>
                                <span className="font-extrabold text-2xl tracking-tight text-slate-900">{t.brand}</span>
                            </div>

                            <nav className="hidden md:flex items-center gap-8 font-medium text-slate-500">
                                <a href="#" className="text-orange-600 font-bold">{t.home}</a>
                                <a href="#" className="hover:text-orange-500 transition-colors">{t.category}</a>
                                <a href="#" className="hover:text-orange-500 transition-colors">{t.about}</a>
                            </nav>

                            <div className="flex items-center gap-4">
                                {/* Language Toggle */}
                                <div className="relative">
                                    <button onClick={() => setIsLangMenuOpen(!isLangMenuOpen)} className="flex items-center gap-2 hover:bg-slate-100 px-3 py-2 rounded-xl transition-colors border border-slate-100">
                                        <i className="fa-solid fa-globe text-slate-600"></i>
                                        <span className="font-bold text-sm">{lang === 'vi' ? 'VN' : 'EN'}</span>
                                    </button>
                                    {isLangMenuOpen && (
                                        <div className="absolute right-0 mt-3 w-40 bg-white rounded-2xl shadow-2xl border border-slate-100 py-2 z-50 animate-zoom-in">
                                            <button onClick={() => { setLang('vi'); setIsLangMenuOpen(false); }} className={`w-full text-left px-5 py-2.5 text-sm ${lang === 'vi' ? 'text-orange-600 font-bold bg-orange-50' : 'text-slate-600 hover:bg-slate-50'}`}>🇻🇳 Tiếng Việt</button>
                                            <button onClick={() => { setLang('en'); setIsLangMenuOpen(false); }} className={`w-full text-left px-5 py-2.5 text-sm ${lang === 'en' ? 'text-orange-600 font-bold bg-orange-50' : 'text-slate-600 hover:bg-slate-50'}`}>🇬🇧 English</button>
                                        </div>
                                    )}
                                </div>
                                <button className="p-2.5 bg-orange-50 text-orange-600 rounded-xl relative">
                                    <i className="fa-solid fa-bag-shopping text-lg"></i>
                                    <span className="absolute -top-1.5 -right-1.5 bg-red-500 text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center border-2 border-white">0</span>
                                </button>
                            </div>
                        </div>
                    </header>

                    {/* HERO SECTION */}
                    <main className="max-w-7xl mx-auto px-4 sm:px-6 pt-28 flex-1">
                        <div className="relative w-full bg-[#FFF8F3] rounded-[2.5rem] p-8 md:p-16 flex items-center justify-between overflow-hidden border border-orange-100/50 mb-12">
                            <div className="relative z-10 max-w-xl">
                                <div className="inline-flex items-center gap-2 bg-orange-100 text-orange-600 px-4 py-1.5 rounded-full text-xs font-extrabold uppercase mb-6">
                                    {t.heroBadge}
                                </div>
                                <h1 className="text-5xl md:text-6xl font-black text-slate-900 mb-6 leading-tight">{t.heroTitle}</h1>
                                <p className="text-slate-600 mb-10 text-lg font-medium">{t.heroDesc}</p>
                                <button className="bg-slate-900 text-white font-bold text-lg py-4 px-10 rounded-full shadow-lg hover:-translate-y-1 transition-transform">
                                    {t.orderNow}
                                </button>
                            </div>
                            <div className="hidden md:block absolute right-0 top-0 bottom-0 w-[50%]">
                                <img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?auto=format&fit=crop&q=80&w=1200" alt="Hero" className="w-full h-full object-cover" />
                                <div className="absolute inset-0 bg-gradient-to-r from-[#FFF8F3] via-[#FFF8F3]/60 to-transparent"></div>
                            </div>
                        </div>

                        {/* CATEGORY FILTER */}
                        <div className="flex gap-3 mb-10 overflow-x-auto pb-4 custom-scrollbar">
                            {t.filters.map((filter) => (
                                <button 
                                    key={filter.id} 
                                    onClick={() => setActiveFilter(filter.id)}
                                    className={`px-6 py-2.5 rounded-full font-semibold whitespace-nowrap transition-all border ${
                                        activeFilter === filter.id 
                                        ? 'bg-slate-900 text-white border-slate-900 shadow-md' 
                                        : 'bg-white text-slate-500 border-slate-200 hover:border-orange-300 hover:text-orange-600'
                                    }`}
                                >
                                    {filter.label}
                                </button>
                            ))}
                        </div>

                        {/* PRODUCT GRID */}
                        <div className="mb-20">
                            <div className="flex justify-between items-end mb-8">
                                <h2 className="text-3xl font-black text-slate-900">{t.featured}</h2>
                                <span className="text-sm font-bold text-slate-400">{filteredProducts.length} {lang === 'vi' ? 'món' : 'items'}</span>
                            </div>

                            <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                                {filteredProducts.map(product => (
                                    <div key={product.id} onClick={() => { setSelectedProduct(product); setQty(1); setSize('M'); }} className="bg-white rounded-3xl p-3 shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition-all group cursor-pointer flex flex-col">
                                        <div className="relative h-52 rounded-2xl overflow-hidden mb-4">
                                            <img src={product.img} alt={product.name_vi} className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                                            <div className="absolute top-3 right-3 bg-white/90 backdrop-blur px-2 py-1 rounded-lg text-[10px] font-bold text-slate-500 uppercase">
                                                {product.category}
                                            </div>
                                        </div>
                                        <div className="flex-1 px-2">
                                            <h3 className="font-bold text-lg text-slate-900 mb-1">{lang === 'vi' ? product.name_vi : product.name_en}</h3>
                                            <p className="text-slate-400 text-xs mb-4 line-clamp-1">{lang === 'vi' ? product.desc_vi : product.desc_en}</p>
                                            <div className="flex items-center justify-between">
                                                <span className="font-black text-xl text-orange-600">{formatPrice(product.price)}</span>
                                                <button className="bg-slate-50 group-hover:bg-orange-500 group-hover:text-white p-2 rounded-xl transition-colors">
                                                    <i className="fa-solid fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    </main>

                    {/* MODAL */}
                    {selectedProduct && (
                        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 animate-fade-in">
                            <div className="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onClick={() => setSelectedProduct(null)}></div>
                            <div className="relative bg-white w-full max-w-4xl rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col md:flex-row max-h-[90vh] animate-zoom-in">
                                <div className="w-full md:w-1/2 h-64 md:h-80 lg:h-auto">
                                    <img src={selectedProduct.img} alt="Detail" className="w-full h-full object-cover" />
                                </div>
                                <div className="w-full md:w-1/2 flex flex-col p-8 bg-white">
                                    <div className="flex-1 overflow-y-auto custom-scrollbar pr-2">
                                        <div className="flex justify-between items-start mb-4">
                                            <h2 className="text-3xl font-black text-slate-900 leading-tight">{lang === 'vi' ? selectedProduct.name_vi : selectedProduct.name_en}</h2>
                                            <button onClick={() => setSelectedProduct(null)} className="text-slate-400 hover:text-slate-900"><i className="fa-solid fa-xmark text-xl"></i></button>
                                        </div>
                                        <p className="text-slate-500 mb-6 leading-relaxed">{lang === 'vi' ? selectedProduct.desc_vi : selectedProduct.desc_en}</p>

                                        <div className="mb-6">
                                            <label className="block font-bold mb-3 text-slate-900">{t.sizeTitle}</label>
                                            <div className="flex gap-3">
                                                {['M', 'L'].map(s => (
                                                    <button key={s} onClick={() => setSize(s)} className={`flex-1 py-3 rounded-2xl font-bold border-2 transition-all ${size === s ? 'border-orange-500 bg-orange-50 text-orange-600 shadow-sm' : 'border-slate-100 text-slate-400 hover:border-slate-200'}`}>
                                                        Size {s} {s === 'L' && '(+10k)'}
                                                    </button>
                                                ))}
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div className="pt-6 border-t border-slate-100 flex items-center gap-4">
                                        <div className="flex items-center bg-slate-100 rounded-xl p-1">
                                            <button onClick={() => qty > 1 && setQty(qty - 1)} className="w-10 h-10 flex items-center justify-center bg-white rounded-lg shadow-sm hover:text-orange-500 transition-colors"><i className="fa-solid fa-minus"></i></button>
                                            <span className="w-10 text-center font-bold text-slate-900">{qty}</span>
                                            <button onClick={() => setQty(qty + 1)} className="w-10 h-10 flex items-center justify-center bg-white rounded-lg shadow-sm hover:text-orange-500 transition-colors"><i className="fa-solid fa-plus"></i></button>
                                        </div>
                                        <button onClick={handleAddToCart} className="flex-1 bg-slate-900 text-white font-bold py-4 rounded-xl flex justify-between px-6 hover:bg-slate-800 transition-all active:scale-[0.98]">
                                            <span>{t.addToCart}</span>
                                            <span>{formatPrice((selectedProduct.price + (size === 'L' ? 10000 : 0)) * qty)}</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    )}

                    {/* TOAST */}
                    {toastMsg && (
                        <div className="fixed bottom-8 left-1/2 -translate-x-1/2 z-[60] animate-slide-up">
                            <div className="bg-slate-900 text-white px-8 py-4 rounded-2xl shadow-2xl flex items-center gap-3">
                                <i className="fa-solid fa-circle-check text-orange-500"></i>
                                <span className="font-bold">{toastMsg}</span>
                            </div>
                        </div>
                    )}
                </div>
            );
        }

        const root = ReactDOM.createRoot(document.getElementById('root'));
        root.render(<App />);
    </script>
</body>
</html>