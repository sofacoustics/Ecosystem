import requests

def toolList():
    """
    Displays the list of all visible Tools in the Ecosystem.
    
    Author: Piotr Majdak (2025)
    
    """
    server_url = 'https://ecosystem.sonicom.eu/tools?type=json'

    # Fetch the list of tools from the Ecosystem
    try:
        response = requests.get(server_url)
        response.raise_for_status()  # Raise an HTTPError for bad responses (4xx or 5xx)
        tools = response.json()

    except requests.exceptions.RequestException as e:
        raise RuntimeError(f"Failed to retrieve file list from server: {server_url}. Error: {e}")

    # Check if correct JSON
    if not isinstance(tools, dict) or 'data' not in tools:
        raise ValueError("Server did not return a valid JSON structure with 'data' field.")

    # Iterate through the tool list and display each
    data = tools['data']
    for item in data:
        tool_id = item.get('ID')
        tool_title = item.get('Title')
        tool_type = item.get('Type')
        tool_url = item.get('URL')
        print(f"Tool #ID {tool_id} ({tool_type}): {tool_title}. URL: {tool_url}")

if __name__ == "__main__":
    toolList()