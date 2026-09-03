import os
import re

file_path = r"C:\laragon\www\uGoForward\resources\js\homepage.js"
with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Replace UNI_DATA
new_uni_data = """// === BASE DE DATOS DE UNIVERSIDADES POR DEPARTAMENTO ===
const UNI_DATA = {
  'san-salvador': {
    name: 'San Salvador',
    region: 'Central Zone',
    unis: [
      {
        name: 'UES',
        fullName: 'University of El Salvador',
        image: 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b7/Logo_de_la_Universidad_de_El_Salvador.svg/1200px-Logo_de_la_Universidad_de_El_Salvador.svg.png',
        youtubeId: null,
        desc: 'The main public higher education institution in the country. Founded in 1841, it offers the largest academic offer nationwide with the widest paid scholarship system in El Salvador.',
        badges: ['🏛️ Public', '👨‍🎓 65,000 Students', '⭐ Founded 1841', '📅 2026 Scholarship Open'],
        careers: ['Medicine', 'Industrial Engineering', 'Law', 'Economics', 'Chemical Sciences', 'Computing', 'Dentistry', 'Architecture', 'Chemistry and Pharmacy'],
        schedule: [
          { dia: 'Monday – Friday', turno: 'Daytime', hora: '7:00 AM – 6:00 PM' },
          { dia: 'Monday – Saturday', turno: 'Nighttime', hora: '5:00 PM – 9:00 PM' },
          { dia: 'Saturday', turno: 'Weekend', hora: '7:00 AM – 5:00 PM' },
        ],
        services: ['🏥 Free Medical Clinic', '📚 24H Central Library', '🍽️ University Cafeteria', '🚌 Public Transport', '💻 IT Labs', '🏋️ Sports', '🎨 Art and Culture', '🔬 Research Center'],
        beca: {
          tipo: 'Paid Scholarship and Tuition Waiver',
          requisitos: [
            { icon: '📊', text: 'Minimum high school GPA of 7.0' },
            { icon: '💰', text: 'Proof of financial need' },
            { icon: '📋', text: 'Certified birth certificate' },
            { icon: '🎯', text: 'Approved aptitude test' },
            { icon: '📆', text: 'Apply before January 2026' },
          ],
        },
        website: 'https://www.ues.edu.sv',
      },
      {
        name: 'UCA',
        fullName: 'Central American University José Simeón Cañas',
        image: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQwjqm5gCq_2DYlnuKO0g7HRGDJfUvSqOXhpQ&s',
        youtubeId: null,
        desc: 'Private university of high prestige and social orientation. Its academic excellence scholarship programs are recognized throughout Central America.',
        badges: ['🎓 Private', '👨‍🎓 8,000 Students', '⭐ High Academic Quality', '🌎 International Recognition'],
        careers: ['Computer Engineering', 'Business Administration', 'Psychology', 'Communications', 'Master of Law', 'Civil Engineering', 'Theology'],
        schedule: [
          { dia: 'Monday – Friday', turno: 'Daytime', hora: '7:00 AM – 7:00 PM' },
          { dia: 'Saturday', turno: 'Weekend', hora: '8:00 AM – 4:00 PM' },
        ],
        services: ['📚 Specialized Library', '💻 Digital Campus', '🌱 Cafeteria', '🔬 Modern Labs', '🎭 Cultural Center', '♿ Full Accessibility'],
        beca: {
          tipo: 'Academic Excellence Scholarship 2026',
          requisitos: [
            { icon: '📊', text: 'High school GPA of 8.5 or higher' },
            { icon: '🏆', text: 'Participation in community activities' },
            { icon: '📋', text: 'Personal motivation letter' },
            { icon: '💼', text: 'Interview with admissions committee' },
            { icon: '📆', text: 'Call for applications: February – March 2026' },
          ],
        },
        website: 'https://www.uca.edu.sv',
      },
    ],
  },
  'santa-ana': {
    name: 'Santa Ana',
    region: 'Western Zone',
    unis: [
      {
        name: 'UNASA',
        fullName: 'Autonomous University of Santa Ana',
        image: 'https://campussostenible.unasa.edu.sv/images/UNASA2024/UNASA_SUR_2024.jpg',
        youtubeId: null,
        desc: 'Leading institution in the western zone specializing in health sciences and technology. It has scholarship agreements with the municipality.',
        badges: ['🏥 Health Specialty', '🌍 Western Zone', '📅 Partial Scholarship Active'],
        careers: ['Medicine', 'Clinical Laboratory', 'Nursing', 'Physiotherapy', 'Nutrition and Dietetics'],
        schedule: [
          { dia: 'Monday – Friday', turno: 'Daytime', hora: '7:30 AM – 6:30 PM' },
          { dia: 'Saturday', turno: 'Complementary', hora: '8:00 AM – 1:00 PM' },
        ],
        services: ['🏥 Practice Clinic', '🔬 Biomedical Lab', '📚 Library', '🍽️ Cafeteria', '🏋️ Sports Area'],
        beca: {
          tipo: 'Academic Merit Scholarship — Western Zone',
          requisitos: [
            { icon: '📊', text: 'Minimum GPA of 8.0 in high school' },
            { icon: '🏠', text: 'Reside in Santa Ana, Ahuachapán or Sonsonate' },
            { icon: '💰', text: 'Family income under $500/month' },
            { icon: '📋', text: 'Applicant or guardian ID' },
          ],
        },
        website: 'https://www.unasa.edu.sv',
      },
      {
        name: 'UCO',
        fullName: 'Catholic University of the West',
        image: 'https://upload.wikimedia.org/wikipedia/commons/d/db/Logo_UCO_unico.png',
        youtubeId: null,
        desc: 'University with Catholic tradition in the heart of Santa Ana with academic offers in law, business and sciences.',
        badges: ['⛪ Private Catholic', '👨‍🎓 4,500 Students', '🏛️ Santa Ana Tradition'],
        careers: ['Law', 'Business Sciences', 'Architecture', 'Civil Engineering', 'Education'],
        schedule: [
          { dia: 'Monday – Friday', turno: 'Daytime and Nighttime', hora: '7:00 AM – 9:00 PM' },
          { dia: 'Saturday', turno: 'Weekend', hora: '7:00 AM – 4:00 PM' },
        ],
        services: ['⛪ University Chapel', '📚 Law Library', '💻 Computer Room', '🎓 Student Welfare'],
        beca: {
          tipo: 'Western Vocational Scholarship',
          requisitos: [
            { icon: '📊', text: 'Minimum GPA 7.5' },
            { icon: '📋', text: 'Parish or religious community letter' },
            { icon: '💰', text: 'Proof of family income' },
          ],
        },
        website: 'https://www.uco.edu.sv',
      },
    ],
  },
  'la-libertad': {
    name: 'La Libertad',
    region: 'South-Central Zone',
    unis: [
      {
        name: 'UTEC',
        fullName: 'Technological University of El Salvador',
        image: 'https://www.utec.edu.sv/images/utec-campus.jpg',
        youtubeId: null,
        desc: 'One of the largest private universities in the country. Strongly oriented towards technology, business and design.',
        badges: ['💻 Technology', '👨‍🎓 30,000 Students', '🌐 National Headquarters', '📅 Active Scholarship'],
        careers: ['Computing', 'Graphic Design', 'Administration', 'Marketing', 'Journalism', 'Electronic Engineering', 'Gastronomy'],
        schedule: [
          { dia: 'Monday – Friday', turno: 'Daytime', hora: '7:00 AM – 6:00 PM' },
          { dia: 'Monday – Saturday', turno: 'Nighttime', hora: '5:30 PM – 9:30 PM' },
        ],
        services: ['💻 Own Data Center', '🎨 Design Studio', '📡 UTEC TV University Channel', '🍽️ Cafeteria', '🏋️ Gym', '🚌 Inter-Campus Buses'],
        beca: {
          tipo: 'Future Digital Scholarship',
          requisitos: [
            { icon: '📊', text: 'Minimum GPA 7.0' },
            { icon: '💰', text: 'Approved socioeconomic study' },
            { icon: '🖥️', text: 'Proven interest in technological areas' },
          ],
        },
        website: 'https://www.utec.edu.sv',
      },
      {
        name: 'UDB',
        fullName: 'Don Bosco University',
        image: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQnJelJuKqLj7HCIIFwKzeFWt2cQHVdvCFEPQ&s',
        youtubeId: null,
        desc: 'Recognized for its excellence in engineering and high-level technical training. Very strong in STEM with top-tier equipment.',
        badges: ['⚙️ Engineering & STEM', '🤖 Mechatronics', '🌟 Top STEM El Salvador', '📅 Open Call'],
        careers: ['Mechatronics', 'Systems Engineering', 'Electronics', 'Industrial', 'Software', 'Biomedical'],
        schedule: [
          { dia: 'Monday – Friday', turno: 'Daytime', hora: '7:00 AM – 6:30 PM' },
          { dia: 'Saturday', turno: 'Special', hora: '8:00 AM – 12:00 PM' },
        ],
        services: ['🔬 Mechatronics Lab', '🏭 Industrial Workshop', '📡 WiFi Campus Network', '🍽️ Cafeteria', '🏋️ Sports Area', '🤝 Business Linkage'],
        beca: {
          tipo: 'STEM Talent Scholarship — Don Bosco',
          requisitos: [
            { icon: '📊', text: 'Minimum GPA 8.0 in STEM subjects' },
            { icon: '🏆', text: 'Participation in science fairs or robotics' },
            { icon: '📋', text: 'Recommendation letter from high school' },
            { icon: '💡', text: 'Scientific skills test' },
          ],
        },
        website: 'https://www.udb.edu.sv',
      },
    ],
  },
  'san-miguel': {
    name: 'San Miguel',
    region: 'Eastern Zone',
    unis: [
      {
        name: 'UES Oriente',
        fullName: 'University of El Salvador — Eastern Campus',
        image: 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b7/Logo_de_la_Universidad_de_El_Salvador.svg/1200px-Logo_de_la_Universidad_de_El_Salvador.svg.png',
        youtubeId: null,
        desc: 'Campus of the University of El Salvador in the east of the country. The most accessible public option for students from San Miguel, Usulután, Morazán and La Unión.',
        badges: ['🏛️ Public', '🌍 Eastern Zone', '📅 Paid Scholarship 2026'],
        careers: ['Law', 'Economics', 'Agronomy', 'Nursing', 'Engineering', 'Education'],
        schedule: [
          { dia: 'Monday – Friday', turno: 'Daytime', hora: '7:00 AM – 6:00 PM' },
          { dia: 'Monday – Saturday', turno: 'Nighttime', hora: '5:00 PM – 9:00 PM' },
        ],
        services: ['📚 Library', '🍽️ Student Cafeteria', '💻 Computer Room', '🏋️ Sports', '🏥 Clinic'],
        beca: {
          tipo: 'Eastern Public Scholarship 2026',
          requisitos: [
            { icon: '📊', text: 'Minimum GPA 7.0' },
            { icon: '🏠', text: 'Reside in the eastern zone of the country' },
            { icon: '💰', text: 'Proven financial need' },
          ],
        },
        website: 'https://www.ues.edu.sv',
      },
      {
        name: 'UGB',
        fullName: 'Gerardo Barrios University',
        image: 'https://www.ugb.edu.sv/images/campus_ugb.jpg',
        youtubeId: null,
        desc: 'The most important private university in the east. Covers all 14 departments with strategic campuses and leadership scholarships.',
        badges: ['🎓 Private', '📍 14 Departments', '🏆 Student Leadership'],
        careers: ['Administration', 'Accounting', 'Law', 'Industrial Engineering', 'Tourism', 'Psychology'],
        schedule: [
          { dia: 'Monday – Friday', turno: 'Daytime and Nighttime', hora: '7:00 AM – 9:00 PM' },
          { dia: 'Saturday', turno: 'Weekend', hora: '7:00 AM – 5:00 PM' },
        ],
        services: ['📚 Digital Library', '💻 Labs', '🎓 Job Board', '🏋️ Sports', '🌐 Virtual Campus'],
        beca: {
          tipo: 'UGB Leadership Scholarship',
          requisitos: [
            { icon: '📊', text: 'Minimum GPA 7.5 in high school' },
            { icon: '🏆', text: 'Show leadership in school community' },
            { icon: '📋', text: 'Recommendation letters' },
            { icon: '📝', text: '500-word essay on academic goals' },
          ],
        },
        website: 'https://www.ugb.edu.sv',
      },
    ],
  },
  'ahuachaapan': {
    name: 'Ahuachapán',
    region: 'Western Zone',
    unis: [
      {
        name: 'UGB — Ahuachapán Campus',
        fullName: 'Gerardo Barrios University — Ahuachapán Campus',
        image: 'https://www.ugb.edu.sv/images/campus_ugb.jpg',
        youtubeId: null,
        desc: 'Campus of the Gerardo Barrios University serving students from the department of Ahuachapán with careers focused on business and technology.',
        badges: ['🎓 Private', '📍 Western Zone', '📅 Leadership Scholarship'],
        careers: ['Business Administration', 'Public Accounting', 'Systems Engineering', 'Law'],
        schedule: [
          { dia: 'Monday – Friday', turno: 'Daytime and Nighttime', hora: '7:00 AM – 9:00 PM' },
          { dia: 'Saturday', turno: 'Weekend', hora: '7:00 AM – 4:00 PM' },
        ],
        services: ['📚 Library', '💻 IT Lab', '🎓 Vocational Counseling', '📶 Campus WiFi'],
        beca: {
          tipo: 'Western Leadership Scholarship — UGB',
          requisitos: [
            { icon: '📊', text: 'Minimum GPA 7.5' },
            { icon: '🏠', text: 'Reside in Ahuachapán' },
            { icon: '🏆', text: 'Active participation in student activities' },
          ],
        },
        website: 'https://www.ugb.edu.sv',
      },
      {
        name: 'UNIVO — Ahuachapán Campus',
        fullName: 'University of the East — Ahuachapán Campus',
        image: 'https://www.univo.edu.sv/assets/images/campus.jpg',
        youtubeId: null,
        desc: 'The University of the East with national presence offers accessible careers for high school graduates from Ahuachapán.',
        badges: ['🌾 Western Zone', '💼 Business Focus', '📅 Admission Open'],
        careers: ['Legal Sciences', 'Business Administration', 'Public Accounting'],
        schedule: [
          { dia: 'Monday – Friday', turno: 'Nighttime', hora: '5:00 PM – 9:00 PM' },
          { dia: 'Saturday', turno: 'Weekend', hora: '8:00 AM – 4:00 PM' },
        ],
        services: ['📚 Library', '💻 Computing', '🎓 Job Board'],
        beca: {
          tipo: 'UNIVO Socioeconomic Scholarship',
          requisitos: [
            { icon: '📊', text: 'Minimum GPA 7.0' },
            { icon: '💰', text: 'Favorable socioeconomic study' },
            { icon: '📋', text: 'Complete personal documents' },
          ],
        },
        website: 'https://www.univo.edu.sv',
      },
    ],
  },
  'sonsonate': {
    name: 'Sonsonate',
    region: 'Western Zone',
    unis: [
      {
        name: 'UNICO',
        fullName: 'University of Sonsonate (UNICO)',
        image: 'https://www.unico.edu.sv/images/campus.jpg',
        youtubeId: null,
        desc: 'The main private institution in Sonsonate, with a focus on business, technology and health careers.',
        badges: ['🏫 Private', '📍 Sonsonate', '📅 Active Call'],
        careers: ['Administration', 'Computing', 'Nutrition', 'Law', 'Accounting'],
        schedule: [
          { dia: 'Monday – Friday', turno: 'Daytime and Nighttime', hora: '7:00 AM – 9:00 PM' },
          { dia: 'Saturday', turno: 'Weekend', hora: '7:00 AM – 4:00 PM' },
        ],
        services: ['📚 Library', '💻 Labs', '🍽️ Cafeteria', '🎓 Student Welfare'],
        beca: {
          tipo: 'Sonsonate Municipal Scholarship',
          requisitos: [
            { icon: '📊', text: 'Minimum GPA 7.5' },
            { icon: '🏠', text: 'Reside in the municipality of Sonsonate' },
            { icon: '💰', text: 'Verified financial need' },
          ],
        },
        website: 'https://www.unico.edu.sv',
      },
    ],
  },
  'chalatenango': {
    name: 'Chalatenango',
    region: 'Northern Zone',
    unis: [
      {
        name: 'UGB — Chalatenango',
        fullName: 'Gerardo Barrios University — Chalatenango Campus',
        image: 'https://www.ugb.edu.sv/images/campus_ugb.jpg',
        youtubeId: null,
        desc: 'Campus in the northern region that provides access to higher education to students from Chalatenango and its municipalities.',
        badges: ['🎓 Private', '📍 Northern Zone', '📅 Leadership Scholarship'],
        careers: ['Business Administration', 'Law', 'Accounting', 'Systems Engineering'],
        schedule: [
          { dia: 'Monday – Saturday', turno: 'Daytime and Nighttime', hora: '7:00 AM – 9:00 PM' },
        ],
        services: ['📚 Library', '💻 IT Lab', '📶 Campus Internet'],
        beca: {
          tipo: 'Northern Leadership Scholarship',
          requisitos: [
            { icon: '📊', text: 'Minimum GPA 7.5' },
            { icon: '🏠', text: 'Reside in the department of Chalatenango' },
            { icon: '🏆', text: 'Community participation' },
          ],
        },
        website: 'https://www.ugb.edu.sv',
      },
    ],
  },
  'cabanas': {
    name: 'Cabañas',
    region: 'North-Central Zone',
    unis: [
      {
        name: 'UNIVO — Cabañas',
        fullName: 'University of the East — Cabañas Campus',
        image: 'https://www.univo.edu.sv/assets/images/campus.jpg',
        youtubeId: null,
        desc: 'Presence of the University of the East in the department of Cabañas, offering accessible careers for the local student population.',
        badges: ['🌾 North-Central Zone', '💼 Cabañas High School Grads', '📅 August Admission'],
        careers: ['Legal Sciences', 'Business Administration', 'Public Accounting'],
        schedule: [
          { dia: 'Monday – Friday', turno: 'Nighttime', hora: '5:30 PM – 9:00 PM' },
          { dia: 'Saturday', turno: 'Full', hora: '8:00 AM – 4:00 PM' },
        ],
        services: ['📚 Library', '💻 Computer Room', '🎓 Academic Counseling'],
        beca: {
          tipo: 'Cabañas Socioeconomic Scholarship',
          requisitos: [
            { icon: '📊', text: 'Minimum GPA 7.0' },
            { icon: '🏠', text: 'Reside in Cabañas' },
            { icon: '💰', text: 'Proven low family income' },
          ],
        },
        website: 'https://www.univo.edu.sv',
      },
    ],
  },
  'cuscatlan': {
    name: 'Cuscatlán',
    region: 'Central Zone',
    unis: [
      {
        name: 'UNICO Cuscatlán',
        fullName: 'University of Cuscatlán',
        image: 'https://www.udecusca.edu.sv/images/campus.jpg',
        youtubeId: null,
        desc: 'Private university based in Cojutepeque, offering access to higher education for the north-central zone of the country.',
        badges: ['🏫 Private', '📍 Cojutepeque', '📅 Partial Scholarship'],
        careers: ['Administration', 'Accounting', 'Legal Sciences', 'Computer Engineering'],
        schedule: [
          { dia: 'Monday – Friday', turno: 'Nighttime', hora: '5:30 PM – 9:00 PM' },
          { dia: 'Saturday', turno: 'Weekend', hora: '8:00 AM – 4:00 PM' },
        ],
        services: ['📚 Library', '💻 Lab', '🎓 Counseling'],
        beca: {
          tipo: 'Cuscatlán Access Scholarship',
          requisitos: [
            { icon: '📊', text: 'Minimum GPA 7.0' },
            { icon: '💰', text: 'Socioeconomic study' },
          ],
        },
        website: '#',
      },
      {
        name: 'UNIVO — Cuscatlán Campus',
        fullName: 'University of the East — Cuscatlán',
        image: 'https://www.univo.edu.sv/assets/images/campus.jpg',
        youtubeId: null,
        desc: 'UNIVO campus in the central zone offering educational continuity for high school graduates from Cuscatlán.',
        badges: ['🌾 Central Zone', '📅 Continuous Admission'],
        careers: ['Administration', 'Law', 'Accounting'],
        schedule: [
          { dia: 'Monday – Saturday', turno: 'Nighttime', hora: '5:30 PM – 9:00 PM' },
        ],
        services: ['📚 Library', '💻 Computing'],
        beca: {
          tipo: 'Socioeconomic Scholarship',
          requisitos: [
            { icon: '📊', text: 'Minimum GPA 7.0' },
            { icon: '💰', text: 'Proven financial need' },
          ],
        },
        website: 'https://www.univo.edu.sv',
      },
    ],
  },
  'la-paz': {
    name: 'La Paz',
    region: 'South-Central Zone',
    unis: [
      {
        name: 'UNIVO — La Paz',
        fullName: 'University of the East — La Paz Campus',
        image: 'https://www.univo.edu.sv/assets/images/campus.jpg',
        youtubeId: null,
        desc: 'Campus in the paracentral zone that serves students from La Paz and surrounding municipalities.',
        badges: ['🌾 Paracentral', '📅 Socioeconomic Scholarship'],
        careers: ['Business Administration', 'Accounting', 'Legal Sciences'],
        schedule: [
          { dia: 'Monday – Saturday', turno: 'Nighttime', hora: '5:30 PM – 9:00 PM' },
        ],
        services: ['📚 Library', '💻 Computing', '🎓 Counseling'],
        beca: {
          tipo: 'La Paz Socioeconomic Scholarship',
          requisitos: [
            { icon: '📊', text: 'Minimum GPA 7.0' },
            { icon: '🏠', text: 'Reside in La Paz' },
            { icon: '💰', text: 'Financial need' },
          ],
        },
        website: 'https://www.univo.edu.sv',
      },
      {
        name: 'UGB — La Paz',
        fullName: 'Gerardo Barrios University — La Paz Campus',
        image: 'https://www.ugb.edu.sv/images/campus_ugb.jpg',
        youtubeId: null,
        desc: 'UGB campus in the paracentral zone with an emphasis on business and legal careers.',
        badges: ['🎓 Private', '📍 Zacatecoluca', '🏆 Leadership Scholarship'],
        careers: ['Administration', 'Law', 'Accounting', 'Systems Engineering'],
        schedule: [
          { dia: 'Monday – Saturday', turno: 'Daytime and Nighttime', hora: '7:00 AM – 9:00 PM' },
        ],
        services: ['📚 Library', '💻 Lab', '🎓 Job Board'],
        beca: {
          tipo: 'Paracentral Leadership Scholarship',
          requisitos: [
            { icon: '📊', text: 'Minimum GPA 7.5' },
            { icon: '🏠', text: 'Reside in La Paz' },
            { icon: '🏆', text: 'Student activism' },
          ],
        },
        website: 'https://www.ugb.edu.sv',
      },
    ],
  },
  'san-vicente': {
    name: 'San Vicente',
    region: 'Paracentral Zone',
    unis: [
      {
        name: 'UES — San Vicente',
        fullName: 'University of El Salvador — San Vicente Paracentral',
        image: 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b7/Logo_de_la_Universidad_de_El_Salvador.svg/1200px-Logo_de_la_Universidad_de_El_Salvador.svg.png',
        youtubeId: null,
        desc: 'UES regional center for the paracentral zone, focusing on health sciences and agricultural sciences.',
        badges: ['🏛️ Public', '🌿 Agricultural', '📅 National Scholarship'],
        careers: ['Nursing', 'Agricultural Technician', 'Education'],
        schedule: [
          { dia: 'Monday – Friday', turno: 'Daytime', hora: '7:00 AM – 5:00 PM' },
        ],
        services: ['📚 Library', '🌿 Agricultural Plot', '🏥 Clinic', '🍽️ Cafeteria'],
        beca: {
          tipo: 'Paracentral Public Scholarship',
          requisitos: [
            { icon: '📊', text: 'Minimum GPA 7.0' },
            { icon: '🏠', text: 'Reside in paracentral zone' },
            { icon: '💰', text: 'Proven financial need' },
          ],
        },
        website: 'https://www.ues.edu.sv',
      },
      {
        name: 'UNIVO — San Vicente',
        fullName: 'University of the East — San Vicente Campus',
        image: 'https://www.univo.edu.sv/assets/images/campus.jpg',
        youtubeId: null,
        desc: 'University campus for San Vicente high school graduates with accessible careers in nighttime hours.',
        badges: ['🌾 Paracentral', '📅 Continuous Admission'],
        careers: ['Administration', 'Accounting', 'Law'],
        schedule: [
          { dia: 'Monday – Saturday', turno: 'Nighttime', hora: '5:30 PM – 9:00 PM' },
        ],
        services: ['📚 Library', '💻 Computing'],
        beca: {
          tipo: 'Socioeconomic Scholarship',
          requisitos: [
            { icon: '📊', text: 'Minimum GPA 7.0' },
            { icon: '💰', text: 'Financial need' },
          ],
        },
        website: 'https://www.univo.edu.sv',
      },
    ],
  },
  'usulutan': {
    name: 'Usulután',
    region: 'Eastern Zone',
    unis: [
      {
        name: 'UGB — Usulután',
        fullName: 'Gerardo Barrios University — Usulután Campus',
        image: 'https://www.ugb.edu.sv/images/campus_ugb.jpg',
        youtubeId: null,
        desc: 'UGB campus in Usulután serving students from the coast and coastal zone of the east.',
        badges: ['🎓 Private', '🌊 Coastal Zone', '🏆 Leadership Scholarship'],
        careers: ['Administration', 'Tourism and Hospitality', 'Accounting', 'Law'],
        schedule: [
          { dia: 'Monday – Saturday', turno: 'Nighttime', hora: '5:30 PM – 9:00 PM' },
        ],
        services: ['📚 Library', '💻 Lab', '🏋️ Sports'],
        beca: {
          tipo: 'Eastern Leadership Scholarship',
          requisitos: [
            { icon: '📊', text: 'Minimum GPA 7.5' },
            { icon: '🏠', text: 'Reside in Usulután' },
          ],
        },
        website: 'https://www.ugb.edu.sv',
      },
    ],
  },
  'morazan': {
    name: 'Morazán',
    region: 'Eastern Zone',
    unis: [
      {
        name: 'UGB — Morazán',
        fullName: 'Gerardo Barrios University — Morazán Campus',
        image: 'https://www.ugb.edu.sv/images/campus_ugb.jpg',
        youtubeId: null,
        desc: 'The only private university campus with an active scholarship in the department of Morazán, facilitating access in the most rural area of the east.',
        badges: ['🎓 Private', '🏔️ Rural Zone', '📅 Rural Access Scholarship'],
        careers: ['Administration', 'Accounting', 'Law'],
        schedule: [
          { dia: 'Monday – Saturday', turno: 'Weekend', hora: '7:00 AM – 5:00 PM' },
        ],
        services: ['📚 Library', '💻 Computing', '🎓 Counseling'],
        beca: {
          tipo: 'Morazán Rural Access Scholarship',
          requisitos: [
            { icon: '📊', text: 'Minimum GPA 7.0' },
            { icon: '🏠', text: 'Reside in Morazán' },
            { icon: '💰', text: 'Financial need' },
          ],
        },
        website: 'https://www.ugb.edu.sv',
      },
      {
        name: 'UNIVO — Morazán',
        fullName: 'University of the East — Morazán Campus',
        image: 'https://www.univo.edu.sv/assets/images/campus.jpg',
        youtubeId: null,
        desc: 'UNIVO campus that provides educational coverage in the mountainous area of Morazán.',
        badges: ['🌾 Rural', '📅 Continuous Admission'],
        careers: ['Business Administration', 'Legal Sciences'],
        schedule: [
          { dia: 'Saturday and Sunday', turno: 'Weekend', hora: '7:00 AM – 5:00 PM' },
        ],
        services: ['📚 Library', '💻 Computing'],
        beca: {
          tipo: 'Rural Socioeconomic Scholarship',
          requisitos: [
            { icon: '📊', text: 'Minimum GPA 7.0' },
            { icon: '💰', text: 'Study of financial need' },
          ],
        },
        website: 'https://www.univo.edu.sv',
      },
    ],
  },
  'la-union': {
    name: 'La Unión',
    region: 'Eastern Zone',
    unis: [
      {
        name: 'UGB — La Unión',
        fullName: 'Gerardo Barrios University — La Unión Campus',
        image: 'https://www.ugb.edu.sv/images/campus_ugb.jpg',
        youtubeId: null,
        desc: 'UGB port campus in La Unión, with careers focused on international trade, logistics and administration.',
        badges: ['⚓ Port Zone', '🚢 Logistics & Trade', '📅 Leadership Scholarship'],
        careers: ['Administration', 'Accounting', 'International Trade', 'Law'],
        schedule: [
          { dia: 'Monday – Saturday', turno: 'Nighttime', hora: '5:30 PM – 9:00 PM' },
        ],
        services: ['📚 Library', '💻 Lab', '🌐 Port Connection'],
        beca: {
          tipo: 'La Unión Leadership Scholarship',
          requisitos: [
            { icon: '📊', text: 'Minimum GPA 7.5' },
            { icon: '🏠', text: 'Reside in La Unión' },
            { icon: '🏆', text: 'Community participation' },
          ],
        },
        website: 'https://www.ugb.edu.sv',
      },
    ],
  },
};
"""

# Pattern to replace the exact block of UNI_DATA
pattern = re.compile(r"// === BASE DE DATOS DE UNIVERSIDADES POR DEPARTAMENTO ===\nconst UNI_DATA = \{.*?\n\};\n", re.DOTALL)
content = pattern.sub(new_uni_data, content)

# String replacements
replacements = {
    "🎓 Carreras": "🎓 Careers",
    "🕐 Horarios": "🕐 Schedules",
    "🛠️ Servicios": "🛠️ Services",
    "⭐ Beca Info": "⭐ Scholarship Info",
    "Días": "Days",
    "Turno": "Shift",
    "Horario": "Schedule",
    "▶ Video": "▶ Video",
    "📷 Foto": "📷 Photo",
    "🌐 Sitio oficial —": "🌐 Official site —",
    "Ver Calendario de Becas →": "View Scholarship Calendar →",
    "Información en construcción": "Information under construction",
    "Próximamente agregaremos las universidades de <strong>${deptId}</strong>. ¡Estamos trabajando en ello!</p>": "We will soon add the universities of <strong>${deptId}</strong>. We are working on it!</p>",
    "universidad${n !== 1 ? 'es' : ''} con becas": "universit${n !== 1 ? 'ies' : 'y'} with scholarships",
    "universidades con becas": "universities with scholarships",
    "universidad con becas": "university with scholarships",
    "Departamento": "Department",
    "1 universidad con becas": "1 university with scholarships",
    "${n} universidades con becas": "${n} universities with scholarships",
    "Test Socioemocional & Orientación Vocacional": "Socioemotional Test & Vocational Guidance",
    "Inicias identificando tus inteligencias múltiples y rasgos socioemocionales. Esto te permite elegir la carrera y universidad con mayor proyección para ti.": "You start by identifying your multiple intelligences and socioemotional traits. This allows you to choose the career and university with the best projection for you.",
    "Hacer el Test Gratis →": "Take the Free Test →",
    "Exploración en el Mapa Territorial": "Exploration on the Territorial Map",
    "Filtra universidades por departamento para descubrir las ofertas académicas más cercanas a tu municipio con programa de becas activo.": "Filter universities by department to discover the closest academic offers to your municipality with an active scholarship program.",
    "Explorar Mapa →": "Explore Map →",
    "Organización en Agenda & Calendario": "Organization in Agenda & Calendar",
    "Añade alertas de cierre y fechas límite para tus entregas de documentos con nuestro semáforo interactivo de urgencias.": "Add closing alerts and deadlines for your document deliveries with our interactive urgency traffic light.",
    "Ver Calendario →": "View Calendar →",
    "Conexión Transparente con Padrinos": "Transparent Connection with Sponsors",
    "Conecta con patrocinadores e instituciones dispuestas a financiar tu educación bajo condiciones justas y directas.": "Connect with sponsors and institutions willing to finance your education under fair and direct conditions.",
    "Conocer Padrinos →": "Meet Sponsors →",
    "¡Zarpar a la Universidad y Triunfar!": "Set Sail for University and Succeed!",
    "Presenta tu admisión respaldada por UGF y comienza tus clases universitarias rumbo a un futuro brillante.": "Present your admission backed by UGF and start your university classes towards a bright future.",
    "Buscar Mi Beca →": "Find My Scholarship →",
    "Paso ": "Step ",
    " de ": " of ",
    "¡Felicidades! Tienes un perfil excepcional para becas completas de Excelencia y Posgrado Internacional con estipendio mensual.": "Congratulations! You have an exceptional profile for full Excellence and International Postgraduate scholarships with a monthly stipend.",
    "Becas Matheadas": "Matched Scholarships",
    "100% Cobertura + Estipendio": "100% Coverage + Stipend",
    "¡Gran perfil! Calificas para becas de Pregrado y Padrinazgo Educativo con cobertura de matrícula y mensualidades.": "Great profile! You qualify for Undergraduate and Educational Sponsorship scholarships with tuition and monthly coverage.",
    "80% - 100% Matrícula": "80% - 100% Tuition",
    "Calificas para programas de apoyo socioeconómico y padrinazgo personalizado en universidades de El Salvador.": "You qualify for socioeconomic support programs and personalized sponsorship at universities in El Salvador.",
    "50% - 75% Arancel": "50% - 75% Fee",
}

for es, en in replacements.items():
    content = content.replace(es, en)

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Translation applied successfully.")
