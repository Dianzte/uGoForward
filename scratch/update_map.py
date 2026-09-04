import re
import json

file_path = r'c:\laragon\www\uGoForward\resources\views\homepage.blade.php'

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Define data for each department
data = {
    'Ahuachapán': [
        {
            "name": "Universidad Panamericana (UPAN)",
            "image": "https://upan.edu.sv/wp-content/uploads/2021/08/logo-upan.png",
            "description": "Universidad con sede en Ahuachapán.",
            "careers": "Administración, Computación, Derecho.",
            "website": "https://upan.edu.sv"
        }
    ],
    'Santa Ana': [
        {
            "name": "UNASA",
            "image": "https://campussostenible.unasa.edu.sv/images/UNASA2024/UNASA_SUR_2024.jpg",
            "description": "Universidad Autónoma de Santa Ana.",
            "careers": "Medicina, Enfermería, Laboratorio Clínico.",
            "website": "https://www.unasa.edu.sv"
        },
        {
            "name": "Universidad Católica de El Salvador (UNICAES)",
            "image": "https://catolica.edu.sv/wp-content/uploads/2019/06/Logo-UNICAES.png",
            "description": "Institución de educación superior católica.",
            "careers": "Ingeniería, Arquitectura, Ciencias de la Salud.",
            "website": "https://www.catolica.edu.sv"
        },
        {
            "name": "Universidad Francisco Gavidia (UFG) - Centro Regional Occidente",
            "image": "https://www.ufg.edu.sv/wp-content/uploads/2021/04/Logo-UFG-horizontal.png",
            "description": "Sede occidental de UFG.",
            "careers": "Negocios, Computación, Derecho.",
            "website": "https://www.ufg.edu.sv"
        }
    ],
    'Sonsonate': [
        {
            "name": "Universidad de Sonsonate (USO)",
            "image": "https://www.uso.edu.sv/wp-content/uploads/2019/08/uso-logo.png",
            "description": "Universidad principal del departamento.",
            "careers": "Ingeniería, Economía, Administración.",
            "website": "https://www.uso.edu.sv"
        },
        {
            "name": "Universidad Modular Abierta (UMA)",
            "image": "https://uma.edu.sv/wp-content/uploads/2019/05/logo-uma-1.png",
            "description": "Sede Sonsonate.",
            "careers": "Derecho, Educación.",
            "website": "https://www.uma.edu.sv"
        }
    ],
    'Chalatenango': [
        {
            "name": "Universidad Monseñor Oscar Arnulfo Romero (UMOAR)",
            "image": "https://umoar.edu.sv/wp-content/uploads/2018/10/logo-umoar-1.png",
            "description": "Universidad en Chalatenango.",
            "careers": "Ciencias Agronómicas, Administración.",
            "website": "https://www.umoar.edu.sv"
        }
    ],
    'La Libertad': [
        {
            "name": "Escuela Superior de Economía y Negocios (ESEN)",
            "image": "https://esen.edu.sv/wp-content/uploads/2018/06/ESEN-Logo.png",
            "description": "Institución enfocada en negocios y economía.",
            "careers": "Economía, Negocios, Ingeniería de Negocios.",
            "website": "https://www.esen.edu.sv"
        },
        {
            "name": "Universidad Dr. José Matías Delgado (UJMD)",
            "image": "https://ujmd.edu.sv/wp-content/uploads/2022/01/Logo-Mat%C3%ADas-2022.png",
            "description": "Universidad privada destacada.",
            "careers": "Comunicaciones, Diseño, Derecho.",
            "website": "https://www.ujmd.edu.sv"
        },
        {
            "name": "ITCA-FEPADE",
            "image": "https://www.itca.edu.sv/wp-content/uploads/2018/07/logo-itca.png",
            "description": "Instituto Tecnológico.",
            "careers": "Ingenierías técnicas, Gastronomía.",
            "website": "https://www.itca.edu.sv"
        }
    ],
    'San Salvador': [
        {
            "name": "Universidad de El Salvador (UES)",
            "image": "https://www.ues.edu.sv/wp-content/uploads/2018/10/ues-logo-1.png",
            "description": "La única universidad pública del país.",
            "careers": "Todas las disciplinas y ramas científicas.",
            "website": "https://www.ues.edu.sv"
        },
        {
            "name": "Universidad Centroamericana José Simeón Cañas (UCA)",
            "image": "https://uca.edu.sv/wp-content/uploads/2021/04/Logo-UCA-horizontal.png",
            "description": "Universidad jesuita reconocida internacionalmente.",
            "careers": "Ingeniería, Ciencias Sociales, Humanidades.",
            "website": "https://www.uca.edu.sv"
        },
        {
            "name": "Universidad Tecnológica de El Salvador (UTEC)",
            "image": "https://www.utec.edu.sv/wp-content/uploads/2021/04/Logo-UTEC-horizontal.png",
            "description": "Universidad en el centro de San Salvador.",
            "careers": "Tecnología, Negocios, Derecho.",
            "website": "https://www.utec.edu.sv"
        },
        {
            "name": "Universidad Don Bosco (UDB)",
            "image": "https://www.udb.edu.sv/wp-content/uploads/2021/04/Logo-UDB-horizontal.png",
            "description": "Especialistas en ingeniería y tecnología.",
            "careers": "Aeronáutica, Mecatrónica, Diseño.",
            "website": "https://www.udb.edu.sv"
        }
    ],
    'Cuscatlán': [
        {
            "name": "Universidad Panamericana (UPAN) - Cuscatlán",
            "image": "https://upan.edu.sv/wp-content/uploads/2021/08/logo-upan.png",
            "description": "Sede en Cojutepeque.",
            "careers": "Administración, Computación.",
            "website": "https://upan.edu.sv"
        }
    ],
    'La Paz': [
        {
            "name": "Universidad Luterana Salvadoreña (ULS) - Centro Regional",
            "image": "https://uls.edu.sv/wp-content/uploads/2021/04/logo-uls.png",
            "description": "Centro educativo en La Paz.",
            "careers": "Trabajo Social, Computación.",
            "website": "https://uls.edu.sv"
        }
    ],
    'Cabañas': [
        {
            "name": "Universidad Luterana Salvadoreña (ULS) - Sensuntepeque",
            "image": "https://uls.edu.sv/wp-content/uploads/2021/04/logo-uls.png",
            "description": "Sede Sensuntepeque.",
            "careers": "Desarrollo Local, Educación.",
            "website": "https://uls.edu.sv"
        }
    ],
    'San Vicente': [
        {
            "name": "UES - Facultad Multidisciplinaria Paracentral",
            "image": "https://www.ues.edu.sv/wp-content/uploads/2018/10/ues-logo-1.png",
            "description": "Sede de la Universidad Nacional en San Vicente.",
            "careers": "Agronomía, Educación, Contaduría.",
            "website": "https://fmp.ues.edu.sv"
        },
        {
            "name": "Universidad Panamericana (UPAN)",
            "image": "https://upan.edu.sv/wp-content/uploads/2021/08/logo-upan.png",
            "description": "Sede San Vicente.",
            "careers": "Enfermería, Informática.",
            "website": "https://upan.edu.sv"
        }
    ],
    'Usulután': [
        {
            "name": "Universidad Gerardo Barrios (UGB) - Sede Usulután",
            "image": "https://ugb.edu.sv/wp-content/uploads/2021/04/logo-ugb.png",
            "description": "Campus regional de UGB.",
            "careers": "Ingeniería, Negocios, Derecho.",
            "website": "https://www.ugb.edu.sv"
        }
    ],
    'San Miguel': [
        {
            "name": "UES - Facultad Multidisciplinaria de Oriente",
            "image": "https://www.ues.edu.sv/wp-content/uploads/2018/10/ues-logo-1.png",
            "description": "Sede oriental de la Universidad Nacional.",
            "careers": "Medicina, Ingeniería, Ciencias.",
            "website": "https://fmo.ues.edu.sv"
        },
        {
            "name": "Universidad de Oriente (UNIVO)",
            "image": "https://univo.edu.sv/wp-content/uploads/2021/04/logo-univo.png",
            "description": "Universidad principal de la zona oriental.",
            "careers": "Arquitectura, Derecho, Medicina.",
            "website": "https://www.univo.edu.sv"
        },
        {
            "name": "Universidad Gerardo Barrios (UGB)",
            "image": "https://ugb.edu.sv/wp-content/uploads/2021/04/logo-ugb.png",
            "description": "Sede principal en San Miguel.",
            "careers": "Ingeniería de Sistemas, Enfermería.",
            "website": "https://www.ugb.edu.sv"
        }
    ],
    'Morazán': [
        {
            "name": "Universidad de El Salvador (Sede)",
            "image": "https://www.ues.edu.sv/wp-content/uploads/2018/10/ues-logo-1.png",
            "description": "Proyectos y sede técnica.",
            "careers": "Carreras Técnicas.",
            "website": "https://www.ues.edu.sv"
        }
    ],
    'La Unión': [
        {
            "name": "MEGATEC La Unión (ITCA-FEPADE)",
            "image": "https://www.itca.edu.sv/wp-content/uploads/2018/07/logo-itca.png",
            "description": "Sede especializada en carreras del mar y logística.",
            "careers": "Logística y Aduanas, Acuicultura.",
            "website": "https://www.itca.edu.sv/la-union"
        }
    ]
}

def replace_path(match):
    full_path = match.group(0)
    dept_name = match.group(1)
    
    # Generate data-universities attribute
    if dept_name in data:
        unis = data[dept_name]
        
        # update data-unis count
        full_path = re.sub(r'data-unis="\d+"', f'data-unis="{len(unis)}"', full_path)
        
        # update data-desc
        desc_list = [u['name'].replace('Universidad ', 'U. ') for u in unis]
        desc_str = ", ".join(desc_list)
        full_path = re.sub(r'data-desc="[^"]*"', f'data-desc="{desc_str}"', full_path)
        
        # remove old data-universities if exists
        full_path = re.sub(r"data-universities='\[.*?\]'", "", full_path)
        
        # insert new data-universities
        json_data = json.dumps(unis, ensure_ascii=False)
        attr = f"data-universities='{json_data}'"
        
        # insert before the d= attribute
        full_path = re.sub(r' d="M', f' {attr} d="M', full_path)
        
    return full_path

# Match lines with <path class="dept ... name="...">
new_content = re.sub(r'<path class="dept cursor-pointer".*?name="([^"]+)"></path>', replace_path, content, flags=re.DOTALL)

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(new_content)

print("Updated homepage.blade.php successfully.")
