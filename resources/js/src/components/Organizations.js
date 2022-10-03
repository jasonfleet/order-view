import { useEffect, useState } from 'react'

function Organizations() {

    const [fetching, setFetching] = useState(false)
    const [orders, setOrders] = useState([])

    const getOrdersData = async () => {
        if (!fetching) {
            setFetching(true)
            const response = await fetch(
                'http://localhost:8080/api/organizations'
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
        <h1 className="text-2xl">Organizations</h1>
        <div className='data-table-container'>
            <table className='data-table'>
                <thead>
                    <tr>
                        <th>School URN</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Telephone</th>
                        <th>URL</th>
                    </tr>
                </thead>
                <tbody>
                    {
                        orders.map((order, i) => {
                            return <tr key={'order-row-' + i}>
                                <td>{order.school_urn}</td>
                                <td>{order.name}</td>
                                <td>{order.email}</td>
                                <td>{order.telephone}</td>
                                <td>{order.url}</td>
                            </tr>
                        })
                    }
                </tbody>

            </table>
        </div>
    </>
}

export default Organizations
