<!DOCTYPE html>
<html>
<head>
    <title>Informe Académico</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd;">
        <h1 style="color: #1a5fb4; text-align: center;">Universidad Continental</h1>
        <h2 style="color: #1a5fb4;">Informe Académico Generado</h2>
        
        <p>Estimado docente,</p>
        
        <p>Se ha generado exitosamente su informe académico con los siguientes resultados:</p>
        
        <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
            <tr>
                <td style="padding: 10px; border: 1px solid #ddd; background: #f0f0f0;"><strong>Estudiantes válidos</strong></td>
                <td style="padding: 10px; border: 1px solid #ddd; text-align: center;">{{ $validos }}</td>
            </tr>
            <tr>
                <td style="padding: 10px; border: 1px solid #ddd; background: #f0f0f0;"><strong>Errores detectados</strong></td>
                <td style="padding: 10px; border: 1px solid #ddd; text-align: center;">{{ $errores }}</td>
            </tr>
        </table>

        <p>El informe completo con gráficos, análisis de IA y predicciones de riesgo se encuentra adjunto en formato PDF.</p>
        
        <p style="background: #e8f4fc; padding: 15px; border-left: 4px solid #1a5fb4;">
            <strong>Nota:</strong> Este sistema utiliza Inteligencia Artificial (Naive Bayes) con 87% de precisión en la predicción de riesgo académico.
        </p>

        <hr style="border: 1px solid #eee; margin: 30px 0;">

        <p style="font-size: 12px; color: #666; text-align: center;">
            <em>
                Sistema desarrollado por:<br>
                Quispe Breña Jean Carlos (74121314) • Quispe Breña Joan Branko (74121315)<br>
                Ramos Rua Alejandro Victor (46392915) • Vega Reyes Steven Andrew (72638273)<br>
                <strong>Asistente Virtual: Zeus</strong>
            </em>
        </p>
    </div>
</body>
</html>