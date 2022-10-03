import { useEffect, useState } from 'react'


const Orders = () => {

    const [fetching, setFetching] = useState(false)
    const [orders, setOrders] = useState([])

    const getOrdersData = async () => {
        if (!fetching) {
            setFetching(true)
            const response = await fetch(
                'http://localhost:8080/api/orders'
            ).then((response) => {
                setFetching(false)
                return response.json()
            })

            setOrders(response)
        }
    }

    useEffect(() => {
        getOrdersData()
    }, [])

    return <>
        <h1 className="text-2xl">Orders</h1>
        <div className='data-table-container'>
            <table className='data-table'>
                <thead>
                    <tr>
                        <th>URN</th>
                        <th>Organization</th>
                        <th className='text-right'>Total</th>
                        <th className='text-right'>Date</th>
                    </tr>
                </thead>
                <tbody>
                    {
                        orders.map((order, i) => {
                            return <tr key={'order-row-' + i}>
                                <td>{order.school_urn}</td>
                                <td>{order.name}</td>
                                <td className='text-right'>{order.bulk_total}</td>
                                <td className='text-right'>{order.created_at}</td>
                            </tr>
                        })
                    }
                </tbody>

            </table>
        </div>
    </>
}

export default Orders
