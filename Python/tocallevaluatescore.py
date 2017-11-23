import urllib2
# If you are using Python 3+, import urllib instead of urllib2

import json 


data =  {

        "Inputs": {

                "input1":
                {
                    "ColumnNames": ["TEXT", "TEXT1"],
                    "Values": [ [ "value", "value" ], [ "value", "value" ], ]
                },        },
            "GlobalParameters": {
}
    }

body = str.encode(json.dumps(data))

url = 'https://ussouthcentral.services.azureml.net/workspaces/f8f1a965407844eca136fe841540357c/services/1d210306b7ad4851b736519711b14bf6/execute?api-version=2.0&details=true'
api_key = 'bOYJ0U1dhr7QWiWUMTgwZSrKClVI+pZ8i0eB4eGctN5qMrTBtC8H+ExErHoN4JPi6HhyrIOYkWGFz6qniD9YZg==' 
headers = {'Content-Type':'application/json', 'Authorization':('Bearer '+ api_key)}

req = urllib2.Request(url, body, headers) 

try:
    response = urllib2.urlopen(req)

    # If you are using Python 3+, replace urllib2 with urllib.request in the above code:
    # req = urllib.request.Request(url, body, headers) 
    # response = urllib.request.urlopen(req)

    result = response.read()
    print(result) 
except urllib2.HTTPError, error:
    print("The request failed with status code: " + str(error.code))

    print(error.info())

    print(json.loads(error.read()))    