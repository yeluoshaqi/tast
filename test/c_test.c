#include <stdio.h>
#include <string.h>

int main() {
	
	char * p;
	char * buffer = "121212,131313";
	char * delims = { " .," };
    
    // buffer=strdup("Find words, all of them.");
	p = strtok(buffer, delims);
	// while(p!=NULL){   
	// 	printf("word: %s\n",p);   
	// 	p=strtok(NULL,delims);   
	// }
	printf("%s\n", buffer);
	return 0;
}