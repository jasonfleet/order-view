import { useState } from "react";
import Orders from "./components/Orders";
import Organizations from "./components/Organizations";
import Products from "./components/Products";
import Upload from "./components/Upload";

function App() {

    const [tab, setTab] = useState('upload')


    return (
        <div className="app">
            <div><h1 className="text-3xl mb-6">Order View</h1></div>

            <div className='buttons'>
                <button className={tab === 'upload' ? 'bg-slate-600' : ''} onClick={() => setTab('upload')} type='button'>Uploads</button>
                <button className={tab === 'organizations' ? 'bg-slate-600' : ''} onClick={() => setTab('organizations')} type='button'>Organizations</button>
                <button className={tab === 'products' ? 'bg-slate-600' : ''} onClick={() => setTab('products')} type='button'>Products</button>
                <button className={tab === 'orders' ? 'bg-slate-600' : ''} onClick={() => setTab('orders')} type='button'>Orders</button>
            </div>

            {tab === 'upload' && <Upload />}

            {tab === 'organizations' && <Organizations />}

            {tab === 'products' && <Products />}

            {tab === 'orders' && <Orders />}

        </div>
    );
}

export default App;
