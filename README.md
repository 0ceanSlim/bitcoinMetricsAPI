## Bitcoin Price Data Retrieval and Storage

This repository contains a pair of scripts to fetch and store Bitcoin price data. The Python script fetches data from a remote API and inserts it into an SQLite database, while the PHP script serves as the API to provide the current Bitcoin price.

### Python Script

**Description**

The Python script (`logPrice.py`) performs the following tasks:

* Fetches the current Bitcoin price from a remote API (https://happytavern.co/bitcoin/api/price.php).
* Creates an SQLite database (`logPrice.db`) and a table to store the price data along with timestamps.
* Inserts the fetched price into the database with a timestamp.

**Usage**

1. Install the required Python libraries if not already installed:

```bash
pip install requests sqlite3
```
2. Run the Python script to start fetching and storing Bitcoin price data:
    (Realistically you would use cron in Linux or Task Sceduler in Windows to perform this automated interval)
```bash
python logPrice.py
```
3. The script will update the price data in the SQLite database.

### PHP Script
**Description**    

The PHP API script (price.php) serves as a simple endpoint for retrieving the current Bitcoin price.

**Usage**    

Place the price.php script on your web server.

Access the API endpoint to fetch the current Bitcoin price:

```bash
curl https://your-server.com/path-to-api/price.php
```
The API will return a JSON response with the current Bitcoin price.

**Example API Response**
```bash
{
    "Price": "28,512.15"
}
```

This README provides an overview of the purpose and usage of the Python script for fetching and storing Bitcoin price data in an SQLite database, as well as the PHP API script for retrieving the current Bitcoin price. Users can follow the instructions to set up and use these scripts in their projects.
