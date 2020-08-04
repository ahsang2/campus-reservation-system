from flask import Flask, render_template, jsonify, request

app = Flask(__name__)
application = app

@app.route("/")
def hello():
   return ("home_page")

"""
# import os
# import sys
from flask import Flask, render_template, jsonify, request
import mysql.connector
from mysql.connector import Error

#sys.path.insert(0, os.path.dirname(__file__))

#def application(environ, start_response):
#    start_response('200 OK', [('Content-Type', 'text/plain')])
#    message = 'this work?'
#    version = 'Python v' + sys.version.split()[0] + '\n'
#    response = '\n'.join([message, version])
#    return [response.encode()]

app = Flask(__name__)
application = app

cnx = mysql.connector.connect(user='reservation_root', password='AbduAlawini',
                              host='localhost',
                              database='reservation_schema')


@app.route("/")
def hello():
   return render_template("home_page.html")
   
@app.route("/bet")
def bet():
    return "bet"
    
@app.route("/get_locations")
def get_locations():
    app.config['JSONIFY_PRETTYPRINT_REGULAR'] = True
    cursor = cnx.cursor()
    query = ("SELECT LocID, LocName FROM Location")
    cursor.execute(query)
    #cursor = cursor.fetchall()
    retDict = {}
    for (LocID, LocName) in cursor:
        retDict[LocID] = LocName
    cursor.close()
    return jsonify(retDict)
    
@app.route("/book")
def book():
    return render_template("form.html")
    
@app.route("/add_user", methods=['POST'])
def add_user():
    cursor = cnx.cursor()
 
    addquery = ("INSERT INTO Student "
               "(FirstName, LastName, NetID, HealthCondition, Dorm) "
               "VALUES (%s, %s, %s, %s, %s)")
    ppl = (request.form.get('FirstName'), request.form.get('LastName'), request.form.get('NetID'), 'Good', request.form.get('Dorm'))
    cursor.execute(addquery,ppl)
    cnx.commit()
    cursor.close()
    return render_template("success.html")
    #user_data = (request.args.get('NetID'), request.args.get('FirstName'), request.args.get('LastName'), "Good", request.args.get('Dorm'))
    #return user_data
    #try:
    #    cursor.execute(add_user, user_data)
    #    cnx.commit()
    #    cursor.close()
    #    return request.args['NetID']
    #except mysql.connector.Error as error:
    #    return error

@app.route("/reservation")
def reservation():
    return '''<!DOCTYPE html>
<html lang="en">
<head><meta charset="utf-8">
	<title>Reserve Home</title>
	<link href="home_css.css" rel="stylesheet" type="text/css" />
</head>
<body>
<h1>Make A Reservation</h1>

<form action="/backend/book_reservation" method="post"><label for="NetID">NetID:</label><br />
<input id="NetID" name="NetID" type="text" /><br />
<label for="Location">Location:</label><br />
<select id="Location" name="Location"><option value="ARC1">Activities and Recreation Center Level 1</option><option value="ARC2">Activities and Recreation Center Level 2</option><option value="ARC3">Activities and Recreation Center Level 3</option><option value="CRCE1">Campus Recreation Center East Level 1</option><option value="CRCE2">Campus Recreation Center East Level 2</option><option value="IKE1">Ikenberry Dining Center Level 1</option><option value="IKE2">Ikenberry Dining Center Level 2</option> </select><br />
<label for="StartTime">Start Time:</label><br />
<input id="StartTime" name="StartTime" type="datetime-local" /><br>
<label for="EndTime">End Time:</label><br />
<input id="EndTime" name="EndTime" type="datetime-local" />
<input type="submit" value="Submit">
</form>
</body>
</html>'''
    
@app.route("/book_reservation", methods=['POST'])
def add_reservation():
    cursor = cnx.cursor()
    addquery = ("INSERT INTO Reservation "
               "(ResID, NetID, LocID, StartDateTime, EndDateTime) "
               "VALUES (NULL, %s, %s, %s, %s)")
    reservationA = (request.form.get('NetID'), request.form.get('Location'), '2020-07-26 10:46:32', '2020-07-26 11:46:32')
    cursor.execute(addquery,reservationA)
    cnx.commit()
    return "Success!"# + request.form.get('StartTime')
    
@app.route("/view_reservations")
def render_reservations():
   # app.config['JSONIFY_PRETTYPRINT_REGULAR'] = True
    cursor = cnx.cursor()
    # import os
# import sys
from flask import Flask, render_template, jsonify, request
import mysql.connector
from mysql.connector import Error

#sys.path.insert(0, os.path.dirname(__file__))

#def application(environ, start_response):
#    start_response('200 OK', [('Content-Type', 'text/plain')])
#    message = 'this work?'
#    version = 'Python v' + sys.version.split()[0] + '\n'
#    response = '\n'.join([message, version])
#    return [response.encode()]

app = Flask(__name__)
application = app

cnx = mysql.connector.connect(user='reservation_root', password='AbduAlawini',
                              host='localhost',
                              database='reservation_schema')

app.config['JSONIFY_PRETTYPRINT_REGULAR'] = True

@app.route("/")
def hello():
   return render_template("home_page.html")
   
@app.route("/bet")
def bet():
    return "bet"
    
@app.route("/get_locations")
def get_locations():
    cursor = cnx.cursor()
    query = ("SELECT LocID, LocName FROM Location")
    cursor.execute(query)
    #cursor = cursor.fetchall()
    retDict = {}
    for (LocID, LocName) in cursor:
        retDict[LocID] = LocName
    cursor.close()
    return jsonify(retDict)
    
@app.route("/book")
def book():
    return render_template("form.html")
    
@app.route("/add_user", methods=['POST'])
def add_user():
    cursor = cnx.cursor()
 
    addquery = ("INSERT INTO Student "
               "(FirstName, LastName, NetID, HealthCondition, Dorm) "
               "VALUES (%s, %s, %s, %s, %s)")
    ppl = (request.form.get('FirstName'), request.form.get('LastName'), request.form.get('NetID'), 'Good', request.form.get('Dorm'))
    cursor.execute(addquery,ppl)
    cnx.commit()
    cursor.close()
    return render_template("success.html")
    #user_data = (request.args.get('NetID'), request.args.get('FirstName'), request.args.get('LastName'), "Good", request.args.get('Dorm'))
    #return user_data
    #try:
    #    cursor.execute(add_user, user_data)
    #    cnx.commit()
    #    cursor.close()
    #    return request.args['NetID']
    #except mysql.connector.Error as error:
    #    return error

@app.route("/reservation")
def reservation():
    return '''<!DOCTYPE html>
<html lang="en">
<head><meta charset="utf-8">
	<title>Reserve Home</title>
	<link href="home_css.css" rel="stylesheet" type="text/css" />
</head>
<body>
<h1>Make A Reservation</h1>

<form action="/backend/book_reservation" method="post"><label for="NetID">NetID:</label><br />
<input id="NetID" name="NetID" type="text" /><br />
<label for="Location">Location:</label><br />
<select id="Location" name="Location"><option value="ARC1">Activities and Recreation Center Level 1</option><option value="ARC2">Activities and Recreation Center Level 2</option><option value="ARC3">Activities and Recreation Center Level 3</option><option value="CRCE1">Campus Recreation Center East Level 1</option><option value="CRCE2">Campus Recreation Center East Level 2</option><option value="IKE1">Ikenberry Dining Center Level 1</option><option value="IKE2">Ikenberry Dining Center Level 2</option> </select><br />
<label for="StartTime">Start Time:</label><br />
<input id="StartTime" name="StartTime" type="datetime-local" /><br>
<label for="EndTime">End Time:</label><br />
<input id="EndTime" name="EndTime" type="datetime-local" />
<input type="submit" value="Submit">
</form>
</body>
</html>'''
    
@app.route("/book_reservation", methods=['POST'])
def add_reservation():
    cursor = cnx.cursor()
    addquery = ("INSERT INTO Reservation "
               "(ResID, NetID, LocID, StartDateTime, EndDateTime) "
               "VALUES (NULL, %s, %s, %s, %s)")
    reservationA = (request.form.get('NetID'), request.form.get('Location'), '2020-07-26 10:46:32', '2020-07-26 11:46:32')
    cursor.execute(addquery,reservationA)
    cnx.commit()
    return "Success!"# + request.form.get('StartTime')
    
@app.route("/view_reservations")
def render_reservations():
   # app.config['JSONIFY_PRETTYPRINT_REGULAR'] = True
    cursor = cnx.cursor()
    query = ("SELECT FirstName, LastName, l.LocID, r.StartDateTime FROM Reservation r NATURAL JOIN Location l NATURAL JOIN Student s")
    cursor.execute(query)
    rows = cursor.fetchall()
   # retDict = {}
   # for (FirstName, LastName, LocID) in cursor:
     #   retDict[FirstName, LastName] = LocID
    #cursor.close()
   # return jsonify(retDict)
   # renderStr = '<h1>Reservations</h1><table><tr><th>First Name</th><th>Last Name</th><th>Location</th><th>Time</th></tr>'
   # cursor = cnx.cursor()
    #query = ("SELECT s.FirstName, s.LastName, l.LocName, r.StartDateTime FROM Reservation r NATURAL JOIN Location l NATURAL JOIN Student s")
    #query = ("SELECT FirstName FROM Students ")
    #query = ("SELECT LocID, LocName FROM Location")
   # cursor.execute(query)
    
    #for (fn, ln, loc, time) in rows:
      #  return fn
      #  renderStr += "<tr><td>" + FirstName + "</td><td>" + LastName + "</td><td>" + LocID + "</td><td>" + StartDateTime + "</td></tr>"
    
   # renderStr += '</table>'
   # cnx.commit()
    cursor.close()
   # return "hello"
    return render_template("view_res.html", people=rows)

   
    
@app.route("/reservation_count")
def reservation_count():
    renderStr = '<h1>Location Count</h1><table><tr><th>Location</th><th>Count</th></tr>'
    cursor = cnx.cursor()
    query = ("SELECT l.LocID as Location, COUNT(l.LocID) as cnt FROM Reservation r NATURAL JOIN Location l GROUP BY l.LocID")
    cursor.execute(query)
    rows = cursor.fetchall()
    
    retDict = {}
    for (Location, cnt) in rows:
        retDict[Location] = cnt
        
    cursor.close()
    return jsonify(retDict)
    
      #  renderStr += "<tr><td>" + {Location} + "</td><td>" + {cnt} + "</td></tr>"
    
    #renderStr += "</table>"
   # return renderStr
    
    #INSERT INTO `Reservation` (`ResID`, `NetID`, `LocID`, `StartDateTime`, `EndDateTime`) VALUES (NULL, 'aasdhg', 'ARC1', '2020-07-26 10:46:32', '2020-07-26 11:46:32')

if __name__ == "__main__":
    app.run(debug=True)

    #cursor = cursor.fetchall()
   # retDict = {}
   # for (FirstName, LastName, LocID) in cursor:
     #   retDict[FirstName, LastName] = LocID
    #cursor.close()
   # return jsonify(retDict)
    renderStr = '<h1>Reservations</h1><table><tr><th>First Name</th><th>Last Name</th><th>Location</th><th>Time</th></tr>'
   # cursor = cnx.cursor()
    #query = ("SELECT s.FirstName, s.LastName, l.LocName, r.StartDateTime FROM Reservation r NATURAL JOIN Location l NATURAL JOIN Student s")
    #query = ("SELECT FirstName FROM Students ")
    #query = ("SELECT LocID, LocName FROM Location")
   # cursor.execute(query)
    
    for (FirstName, LastName, LocID, StartDateTime) in cursor:
        renderStr += "<tr><td>" + FirstName + "</td><td>" + LastName + "</td><td>" + LocID + "</td><td>" + StartDateTime + "</td></tr>"
    
    renderStr += "</table>"
   # cnx.commit()
    cursor.close()
    return renderStr
   
    
@app.route("/reservation_count")
def reservation_count():
    renderStr = '<h1>Location Count</h1><table><tr><th>Location</th><th>Count</th></tr>'
    cursor = cnx.cursor()
    query = ("SELECT l.LocID as Location, COUNT(l.LocID) as cnt FROM Reservation r NATURAL JOIN Location l GROUP BY l.LocID")
    cursor.execute(query)
    
    for (Location, cnt) in cursor:
        renderStr += "<tr><td>" + Location + "</td><td>" + cnt + "</td></tr>"
    
    renderStr += "</table>"
    return renderStr
    
    #INSERT INTO `Reservation` (`ResID`, `NetID`, `LocID`, `StartDateTime`, `EndDateTime`) VALUES (NULL, 'aasdhg', 'ARC1', '2020-07-26 10:46:32', '2020-07-26 11:46:32')

if __name__ == "__main__":
    app.run(debug=True)
"""