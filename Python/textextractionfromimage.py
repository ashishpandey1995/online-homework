
import httplib, urllib, base64, json

subscription_key = '8dc3cc2376234af696b9de978095f76a'

uri_base = 'westcentralus.api.cognitive.microsoft.com'

headers = {
  
    'Content-Type': 'application/json',
    'Ocp-Apim-Subscription-Key': subscription_key,
}

params = urllib.urlencode({
   
    'language': 'unk',
    'detectOrientation ': 'true',
})


body = "{'url':'http://jeroen.github.io/images/testocr.png'}"

try:
    
    conn = httplib.HTTPSConnection('westcentralus.api.cognitive.microsoft.com')
    conn.request("POST", "/vision/v1.0/ocr?%s" % params, body, headers)
    response = conn.getresponse()
    data = response.read()

   
    parsed = json.loads(data)
    print ("Response:")
    print (json.dumps(parsed, sort_keys=True, indent=2))
    conn.close()

except Exception as e:
    print('Error:')
    print(e)

