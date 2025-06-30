import os
import requests
import json
import argparse

def databaseDownload(download_path: str, database_id: int):
    """
    Downloads datafiles from the Ecosystem.

    Parameters:
        download_path (str): Local directory where the files will be downloaded.
        database_id (int): ID of the database, see database_list (in original MATLAB context).

    The local structure will be: downloadPath/datasetName/datafileType/DatafileName
    
    Author: Piotr Majdak (2025)
    
    """

    # Check if the download path exists
    if not os.path.isdir(download_path):
        try:
            os.makedirs(download_path)
        except OSError as e:
            raise Exception(f'downloadFilesFromHTTPServer:createFolder - Failed to create download directory: {download_path}. Error: {e}')

    # Fetch the list of files from the Ecosystem
    server_url = f'https://ecosystem.sonicom.eu/databases/{database_id}/download?type=json'
    try:
        response = requests.get(server_url)
        response.raise_for_status()  # Raise an HTTPError for bad responses (4xx or 5xx)
        json_data = response.json()
    except requests.exceptions.RequestException as e:
        raise Exception(f'downloadFilesFromHTTPServer:getFileList - Failed to retrieve file list from server: {server_url}. Error: {e}')
    except json.JSONDecodeError as e:
        raise Exception(f'downloadFilesFromHTTPServer:serverError - Server returned invalid JSON. Error: {e}. Server response: {response.text}')

    # Check if correct JSON
    if not isinstance(json_data, dict):
        raise Exception('downloadFilesFromHTTPServer:invalidFormat - Server did not return a dictionary (struct) of file information.')
    if 'data' not in json_data:
        raise Exception('downloadFilesFromHTTPServer:invalidFormat - Server did not return a JSON file with "data" field.')

    # Iterate through the datafile list and download each file
    data = json_data.get('data', [])
    if not data:
        print('This Database does not contain any Datafiles\n')
    else:
        for item in data:
            file_url = item.get('Datafile URL')
            file_name = item.get('Datafile Name')
            dataset_name = item.get('Dataset Name')
            datafile_type = item.get('Datafile Type')

            if not all([file_url, file_name, dataset_name, datafile_type]):
                print(f"Datafile Type {datafile_type} from Dataset {dataset_name} not available")
                continue

            dataset_dir = os.path.join(download_path, dataset_name)
            datafile_type_dir = os.path.join(dataset_dir, datafile_type)

            if not os.path.isdir(dataset_dir):
                os.makedirs(dataset_dir)
            if not os.path.isdir(datafile_type_dir):
                os.makedirs(datafile_type_dir)

            local_file_path = os.path.join(datafile_type_dir, file_name)

            # download the Datafile
            try:
                print(f'Downloading {file_name} from Dataset {dataset_name}...')
                # Stream the download for potentially large files
                with requests.get(file_url, stream=True) as r:
                    r.raise_for_status()
                    with open(local_file_path, 'wb') as f:
                        for chunk in r.iter_content(chunk_size=8192):
                            f.write(chunk)
            except requests.exceptions.RequestException as e:
                raise Exception(f'downloadFilesFromHTTPServer:downloadError - Failed to download file: {file_name} from {file_url} to {local_file_path}. Error: {e}')
        print('Download completed...')
        
if __name__ == "__main__":
    parser = argparse.ArgumentParser(description="Download a Database from the SONICOM Ecosystem.")
    parser.add_argument("path", type=str, help="The local path where to download the Database.")
    parser.add_argument("-id", type=int, help="The ID of the Database.")

    args = parser.parse_args()
    databaseDownload(args.path, args.id)
