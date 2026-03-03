<!DOCTYPE html>
<html>
<head>
    <style>
        .modal { display: none; position: fixed; background: rgba(0,0,0,0.5); width: 100%; height: 100%; }
        .modal-content { background: white; margin: 20% auto; padding: 20px; width: 300px; }
    </style>
</head>
<body>
    <button onclick="document.getElementById('miModal').style.display='block'">
        Abrir Modal
    </button>
    
    <div id="miModal" class="modal">
        <div class="modal-content">
            <span onclick="document.getElementById('miModal').style.display='none'">✖</span>
            <h3>Test Modal</h3>
            <p>Si ves esto, los modales funcionan</p>
        </div>
    </div>
</body>
</html>