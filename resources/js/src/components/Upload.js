function Upload() {


    const handleSubmit = () => {

    }

    return <>
        <h1 className="text-2xl">Uploads</h1>
        <div>
            <div className='mb-4'>
                <h2>All (Organizations, Products and Orders</h2>
                <form onSubmit={handleSubmit()}>
                    <input type='file' />
                    <input type="submit" />
                </form>
            </div>
        </div>
        <div>
            <div className='mb-4'>
                <h2>Organizations Only</h2>
                <form onSubmit={handleSubmit()}>
                    <input type='file' />
                    <input type="submit" />
                </form>
            </div>
        </div>

        <div>
            <div className='mb-4'>
                <h2>Products Only</h2>
                <form onSubmit={handleSubmit()}>
                    <input type='file' />
                    <input type="submit" />
                </form>
            </div>
        </div>
        <div>
            <div className='mb-4'>
                <h2>Orders Only</h2>
                <form onSubmit={handleSubmit()}>
                    <input type='file' />
                    <input type="submit" />
                </form>
            </div>
        </div>
    </>
}

export default Upload
