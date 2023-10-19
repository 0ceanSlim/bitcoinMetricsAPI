import requests
import sqlite3
from datetime import datetime

# Function to fetch Bitcoin price data from the API
def fetch_bitcoin_price():
    url = "https://happytavern.co/bitcoin/api/price.php"
    response = requests.get(url)
    if response.status_code == 200:
        data = response.json()
        price = data["Price"]
        return price
    else:
        return None

# Function to create the SQLite database and table
def create_database():
    conn = sqlite3.connect("logPrice.db")
    cursor = conn.cursor()
    cursor.execute("""
        CREATE TABLE IF NOT EXISTS priceHistory (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            timestamp DATETIME,
            price TEXT
        )
    """)
    conn.commit()
    conn.close()

# Function to insert price data into the database with a timestamp
def insert_price_into_database(price):
    conn = sqlite3.connect("logPrice.db")
    cursor = conn.cursor()
    timestamp = datetime.now()
    cursor.execute("INSERT INTO priceHistory (timestamp, price) VALUES (?, ?)", (timestamp, price))
    conn.commit()
    conn.close()

if __name__ == "__main__":
    create_database()

    # Fetch the Bitcoin price
    price = fetch_bitcoin_price()
    
    if price is not None:
        insert_price_into_database(price)
        print(f"Price data inserted: {price} at {datetime.now()}")
    else:
        print("Failed to fetch Bitcoin price data.")

# The script will exit when it's done running.
