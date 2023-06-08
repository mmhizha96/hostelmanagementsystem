import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ResavationsComponent } from './resavations.component';

describe('ResavationsComponent', () => {
  let component: ResavationsComponent;
  let fixture: ComponentFixture<ResavationsComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ ResavationsComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(ResavationsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
