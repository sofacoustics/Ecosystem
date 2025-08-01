import requests

def databaseList():
    """
    Displays the list of all visible Databases in the Ecosystem.
    
    Author: Piotr Majdak (2025)
    
    """
    server_url = 'https://ecosystem.sonicom.eu/databases?type=json'

    # Fetch the list of databases from the Ecosystem
    try:
        response = requests.get(server_url)
        response.raise_for_status()  # Raise an HTTPError for bad responses (4xx or 5xx)
        databases = response.json()

    except requests.exceptions.RequestException as e:
        raise RuntimeError(f"Failed to retrieve file list from server: {server_url}. Error: {e}")

    # Check if correct JSON
    if not isinstance(databases, dict) or 'data' not in databases:
        raise ValueError("Server did not return a valid JSON structure with 'data' field.")

    # Iterate through the database list and display each
    data = databases['data']
    for item in data:
        database_id = item.get('ID')
        database_url = item.get('URL')
        database_title = item.get('Title')
        print(f"Database #ID {database_id}: {database_title}. URL: {database_url}")

if __name__ == "__main__":
    databaseList()