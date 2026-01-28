import { useEffect, useState } from 'react';
import api from '../api/axios';

const ProductList = () => {
    const [products, setProducts] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        fetchProducts();
    }, []);

    const fetchProducts = async () => {
        try {
            const response = await api.get('/products');
            setProducts(response.data);
            setLoading(false);
        } catch (err) {
            console.error('Error fetching products:', err);
            setError('Failed to fetch products');
            setLoading(false);
        }
    };

    if (loading) return <div className="p-4 text-white">Loading products...</div>;
    if (error) return <div className="p-4 text-red-500">{error}</div>;

    return (
        <div className="p-6">
            <h2 className="text-2xl font-bold mb-6 text-white">Product List (API Test)</h2>
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                {products.map(product => (
                    <div key={product.id} className="bg-gray-800 rounded-xl p-4 border border-gray-700 hover:border-blue-500 transition-all">
                        <h3 className="text-xl font-semibold text-white">{product.name}</h3>
                        <p className="text-gray-400 text-sm mt-1">{product.category?.name} - {product.brand?.name}</p>
                        <p className="text-blue-400 font-bold mt-2">${product.price}</p>
                        <p className="text-gray-300 mt-2 line-clamp-2">{product.description}</p>
                        <div className="mt-4 flex items-center justify-between">
                            <span className={`px-2 py-1 rounded text-xs ${product.stock > 0 ? 'bg-green-900 text-green-300' : 'bg-red-900 text-red-300'}`}>
                                {product.stock > 0 ? `In Stock: ${product.stock}` : 'Out of Stock'}
                            </span>
                        </div>
                    </div>
                ))}
            </div>
        </div>
    );
};

export default ProductList;