import requests
import pandas as pd


def importar_datos(df, api_url, api_token):
    """
    Agarra un dataframe, lo parsea a json y lo importa a la tabla de personal de la app
    
    Parametros
    df: dataframe con los datos a importar
    api_url: el url del endpoint de la api
    api_toke: api key de acceso

    Retorna
    Un diccionario con el resultado de la importación
    """

    datos = preparar_datos(df)

    headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    }

    if api_token:
        headers['Authorization'] = f'Bearer {api_token}'
        print("Usando autenticación por token")

    try:
        response = requests.post(
            api_url,
            json=datos,
            headers= headers,
            timeout=30
        )

        if response.status_code == 201:
            return {
                'success': True,
                'message': 'Importacion exitosa',
                'data': response.json()
            }
        else:
            return {
                'success': False,
                'message': f'Error: {response.status_code}',
                'error': response.text,
                'headers_sent': headers
            }
        
    except requests.exceptions.RequestException as e:
        return {
            'success': False,
            'message': f'Error: {str(e)}',
            'error': type(e).__name__
        }
    except Exception as e:
        return {
            'success': False,
            'message': f'Error: {str(e)}',
            'error': type(e).__name__
        }

def preparar_datos(df):
    """

    Prepara los datos de un dataframe para la serialización JSON.
    Convierte timestamps, NaT y otros tipos de datos que puedan llegar a ser problematicos
    
    """

    df_limpio = df.copy()

    df_limpio['nro_doc'] = df_limpio['nro_doc'].astype(str)

    # Agregar campos faltantes con valores por defecto
    df_limpio['cliente_id'] = 1  # Valor por defecto
    df_limpio['puesto'] = 'Sin definir'
    df_limpio['cargo'] = 'Sin definir' 
    df_limpio['telefono'] = ''
    
    if 'tipo_doc' not in df_limpio.columns:
        df_limpio['tipo_doc'] = 'DU'

    df_limpio['fecha_ing'] = df_limpio['fecha_ing'].dt.strftime('%Y-%m-%d')

    df_limpio = df_limpio.where(pd.notnull(df_limpio), None)

    return df_limpio.to_dict('records')


def verificar_registros(df, api_url):
    """

    Verifica múltiples registros contra la API de verificación masiva

    """
    datos = preparar_datos(df)
    
    try:
        response = requests.post(
            api_url,  
            json=datos,
            headers={
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            timeout=60
        )
        
        if response.status_code == 200:
            resultado = response.json()
            return {
                'success': True,
                'estadisticas': {
                    'total': resultado['total_registros'],
                    'existentes': resultado['registros_existentes'],
                    'nuevos': resultado['registros_nuevos'],
                    'errores': resultado['errores']
                },
                'resultados': resultado['resultados']
            }
        else:
            return {
                'success': False,
                'message': f"Error API: {response.status_code}",
                'error': response.text
            }
            
    except Exception as e:
        return {
            'success': False,
            'message': f"Error conexión: {str(e)}"
        }