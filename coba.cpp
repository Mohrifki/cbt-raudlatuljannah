#include <iostream>
using namespace std;
void lulus(string nama, int nilai) {
    if (nilai >= 80) {
        cout << "selamat " << " ananda " << nama << " dengan nilai " << nilai << " dinyatakan lulus"<< endl;
     }else {
        cout << "mohon maaf! " << "ananda " << nama << "dengan nilai " << nilai << " dinyatakan tidak lulus" <<endl;
     }
}
int main() {
    lulus("hafy", 86);
    lulus("erlangga", 75);
    lulus("zaky", 65);

    return 0;
}