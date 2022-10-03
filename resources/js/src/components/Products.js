import { useEffect, useState } from 'react'

function Products() {
    const [fetching, setFetching] = useState(false)
    const [orders, setOrders] = useState([])

    const getOrdersData = async () => {
        if (!fetching) {
            setFetching(true)
            const response = await fetch(
                'http://localhost:8080/api/products'
            ).then((response) => {
                setFetching(false)
                return response.json()
            })

            setOrders(response)
        }
    }

    const showOrder = (orderId) => {
        console.log(orderId)
    }

    useEffect(() => {
        getOrdersData()
    }, [])

    return <>
        <h1 className="text-2xl">Products</h1>
        <div className='data-table-container'>
            <table className='data-table'>
                <thead>
                    <tr>
                        <th>Style Ref</th>
                        <th>Name</th>
                        <th>Colour Name</th>
                        <th>Colour Image URL</th>
                        <th>EAN</th>
                    </tr>
                </thead>
                <tbody>
                    {
                        orders.map((order, i) => {
                            return <tr onClick={() => showOrder(order.id)} key={'order-row-' + i}>
                                <td>{order.colour_style_ref}</td>
                                <td>{order.name}</td>
                                <td>{order.colour_name}</td>
                                <td>{order.colour_image_url}</td>
                                <td>{order.ean}</td>
                            </tr>
                        })
                    }
                </tbody>

            </table>
        </div>
    </>
}

export default Products
